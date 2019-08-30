<?php

namespace App\Http\Controllers\User;

use App\Models\Good;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use PayPal\Api\PaymentExecution;


class PayPalController extends Controller
{
    private $clientId;//ID
    private $clientSecret;//秘钥
    protected $accept_url = '/api/user/pay/callback';//返回地址
    const Currency = 'USD';//币种
    protected $PayPal;

    public function __construct()
    {
        $this->clientId = env('PAY_CLIENT_ID', '');
        $this->clientSecret = env('PAY_CLIENT_SECRET', '');
        $this->accept_url = env('APP_URL', ''). $this->accept_url;

        $this->PayPal = new ApiContext(
            new OAuthTokenCredential(
                $this->clientId,
                $this->clientSecret
            )
        );
        //如果是沙盒测试环境不设置，请注释掉
//        $this->PayPal->setConfig(
//            array(
//                'mode' => 'live',
//            )
//        );
    }

    /**
     * @param
     * $product 商品
     * $price 价钱
     * $shipping 运费
     * $description 描述内容
     */
    public function pay(Request $request)
    {

//        $cart_data = collect([
//            [
//                "sku_id" => 1001,
//                "price"=> 9900,
//                "sku_nums"=> 1,
//                "good_id"=> 74
//            ],
//            [
//                "sku_id" => 1002,
//                "price"=> 9000,
//                "sku_nums"=> 1,
//                "good_id"=> 73
//            ],
//            [
//                "sku_id" => 2001,
//                "price"=> 100,
//                "sku_nums"=> 1,
//                "good_id"=> 24
//            ]
//        ]);

        $cart_data = collect($request->post('cart_data'));

        $cart_data = $cart_data->map(function($item){
            $item['price'] = round($item['price']/100,2);
            return $item;
        });


        $total_price = $cart_data->map(function($item){
            return $item['price'] * $item['sku_nums'];
        })->sum();

        $item_list = [];
        foreach ($cart_data as $data){
            $item = new Item();
            $good = Good::find($data['good_id']);
            $item->setName($good->title)->setCurrency(self::Currency)->setQuantity($data['sku_nums'])->setPrice($data['price'] * $data['sku_nums']);
            array_push($item_list, $item);
        }

        $shipping = 0;
        $description = '';
        $paypal = $this->PayPal;
        $total = $total_price + $shipping;//总价

        $payer = new Payer();
        $payer->setPaymentMethod('paypal');

        $itemList = new ItemList();
        $itemList->setItems($item_list);

        $details = new Details();
        $details->setShipping($shipping)->setSubtotal($total);

        $amount = new Amount();
        $amount->setCurrency(self::Currency)->setTotal($total)->setDetails($details);

        $transaction = new Transaction();
        $transaction->setAmount($amount)->setItemList($itemList)->setDescription($description)->setInvoiceNumber(uniqid());

        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(self::accept_url . '?success=true')->setCancelUrl(self::accept_url . '/?success=false');

        $payment = new Payment();
        $payment->setIntent('sale')->setPayer($payer)->setRedirectUrls($redirectUrls)->setTransactions([$transaction]);

        try {
            $payment->create($paypal);
        } catch (PayPalConnectionException $e) {
            echo $e->getData();
            die();
        }

        $approvalUrl = $payment->getApprovalLink();
        return returned(true,'',['url' =>$approvalUrl]);
    }

    /**
     * 回调
     */
    public function pay_callback()
    {
        $success = trim($_GET['success']);

        if ($success == 'false' && !isset($_GET['paymentId']) && !isset($_GET['PayerID'])) {
            echo '取消付款';die;
        }

        $paymentId = trim($_GET['paymentId']);
        $PayerID = trim($_GET['PayerID']);

        if (!isset($success, $paymentId, $PayerID)) {
            echo '支付失败';die;
        }

        if ((bool)$_GET['success'] === 'false') {
            echo  '支付失败，支付ID【' . $paymentId . '】,支付人ID【' . $PayerID . '】';die;
        }

        $payment = Payment::get($paymentId, $this->PayPal);

        $execute = new PaymentExecution();

        $execute->setPayerId($PayerID);

        try {
            $payment->execute($execute, $this->PayPal);
        } catch (Exception $e) {
            echo ',支付失败，支付ID【' . $paymentId . '】,支付人ID【' . $PayerID . '】';die;
        }
        echo '支付成功，支付ID【' . $paymentId . '】,支付人ID【' . $PayerID . '】';die;
    }
}
