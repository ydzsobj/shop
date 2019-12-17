<?php

namespace App\Http\Controllers\User;

use App\Models\Good;
use App\Models\GoodModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;

class GoodController extends Controller
{
    /**
     * @api {get} /api/user/goods  1.3 商品列表
     * @apiName GetGoods
     * @apiGroup User
     *
     * @apiParam {Number} [category_id] 商品类别ID
     * @apiParam {Number} [good_module_id] 商品模块id
     * @apiParam {Number} [page] 当前分页
     * @apiParam {String} [keywords] 搜索关键词
     *
     * @apiSuccess {Array} data 商品列表数据
     * @apiSuccess {Number} current_page 当前页码
     * @apiSuccess {Number} last_page 最后一页（总页数）
     * @apiSuccess {string} prev_page_url 上一页链接
     * @apiSuccess {string} last_page_url 下一页链接
     * @apiSuccess {Number} total 总条数
     *
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *{
     *    "success": true,
     *    "msg": "",
     *    "data": {
     *        "current_page": 1,
     *        "data": [
     *            {
     *                "goodsId": 51,
     *                "name": "微软（Microsoft）Surface Laptop 2 超轻薄触控笔记本（13.5英寸 第八代Core i5 8G 256G SSD ）典雅黑",
     *                "mallPrice": "990999.00",
     *                "price": "8888.00",
     *                "good_module_id": null,
     *                "image": "http://192.168.1.132:8081/storage/uploads/image/2019/08/11/0ae29c8dff6c517727e4806a6f5ce96b.png"
     *            },
     *            {
     *                "goodsId": 50,
     *                "name": "微软（Microsoft）Surface Laptop 2 超轻薄触控笔记本（13.5英寸 第八代Core i5 8G 256G SSD ）典雅黑",
     *                "mallPrice": "9999.00",
     *                "price": "8888.00",
     *                "good_module_id": null,
     *                "image": "http://192.168.1.132:8081/storage/uploads/image/2019/08/11/251095c01dca97db3e3479a61f2b2941.png"
     *            },
     *            {
     *                "goodsId": 49,
     *                "name": "李宁 7号6号5号篮球女青少年儿童篮球小学生室外成人耐磨正品蓝球",
     *                "mallPrice": "1000.00",
     *                "price": "900.00",
     *                "good_module_id": 2,
     *                "image": "http://192.168.1.132:8081/storage/uploads/image/2019/08/09/4df0209b6471426a22f0b200497a490f.jpeg"
     *            },
     *            {
     *                "goodsId": 48,
     *                "name": "测试-无规格",
     *                "mallPrice": "899.88",
     *                "price": "88.99",
     *                "good_module_id": 2,
     *                "image": "http://192.168.1.132:8081/storage/uploads/image/2019/08/08/03b5ed3f0111edeeaa393c075c0d34f4.png"
     *            },
     *        ],
     *        "first_page_url": "http://192.168.1.132:8081/api/user/goods?page=1",
     *        "from": 1,
     *        "last_page": 2,
     *        "last_page_url": "http://192.168.1.132:8081/api/user/goods?page=2",
     *        "next_page_url": "http://192.168.1.132:8081/api/user/goods?page=2",
     *        "path": "http://192.168.1.132:8081/api/user/goods",
     *        "per_page": 20,
     *        "prev_page_url": null,
     *        "to": 20,
     *        "total": 28
     *    }
     *}
     */
    public function index(Request $request){

        $keywords = $request->get('keywords');

        if($request->has('keywords') && is_null($keywords)){
            return returned(true,'',['data' => []]);
        }

        $gd = new Good();
        $goods = $gd->user_good_data($request->all());

        return returned(true, '', $goods);
    }

    /**
     * @api {get} /api/user/goods/{id}  1.1 商品详情
     * @apiName GetGoodDetails
     * @apiGroup User
     *
     * @apiParam {Number} id 商品id
     *
     * @apiSuccess {String} title 商品标题
     * @apiSuccess {String} about 商品简介
     * @apiSuccess {String} price 实际价格
     * @apiSuccess {Number} show_coupon_code 是否展示优惠码输入 0不显示 1展示
     * @apiSuccess {Array} pay_types 付款方式
     * @apiSuccess {Number} show_comment 是否显示评论模块
     * @apiSuccess {string} detail_desc 商品详情
     * @apiSuccess {string} size_desc 尺寸描述
     * @apiSuccess {string} main_image_url 封面主图
     * @apiSuccess {string} main_video_url 视频地址
     * @apiSuccess {array} list_images 轮播图地址
     * @apiSuccess {array} tree 商品属性规格
     * @apiSuccess {array} list 商品skus
     * @apiSuccess {array} comments 商品评价
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *{
     *    "good": {
     *        "id": 24,
     *        "title": "乔丹男鞋2019夏新款跑鞋男网面透气休闲鞋子轻便时尚跑步鞋运动鞋男 曜石黑231 44.5",
     *        "name": "08020937-运动鞋",
     *        "about": "这是一条简介。。。",
     *        "original_price": "111.00",
     *        "price": "99.00",
     *        "show_coupon_code": 0,//是否展示优惠码输入
     *        "product_id": 1,
     *        "product_name": null,
     *        "admin_user_id": 1,
     *        "category_id": 1,
     *        "pay_types": [
     *            "pay_arrived",
     *            null
     *        ],
     *        "show_comment": 1,
     *        "detail_desc": "<h1 style=\"text-align:center;\">\r\n\t<strong>这是商品描述</strong>\r\n</h1>\r\n<p>\r\n\t<table>\r\n\t\t<tbody>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-content\" style=\"text-align:center;\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;p&gt;</span>7月30日召开的中共中央政治局会议要求，要紧紧围绕&amp;ldquo;巩固、增强、提升、畅通&amp;rdquo;八字方针，深化供给侧结构性改革，提升产业基础能力和产业链水平。这充分彰显了以习近平同志为核心的党中央把握我国经济发展长期大势，抓住主要矛盾，善于化危为机，办好自己的事的科学战略，也充分表明了坚持深化供给侧结构性改革的坚定决心和信心。<span class=\"html-tag\">&lt;/p&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"124\" style=\"text-align:center;\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;p <span class=\"html-attribute-name\">style</span>=\"<span class=\"html-attribute-value\">text-align: center;</span>\"&gt;</span><span class=\"html-tag\">&lt;img <span class=\"html-attribute-name\">src</span>=\"<a class=\"html-attribute-value html-resource-link\" target=\"_blank\" href=\"https://p4.img.cctvpic.com/photoworkspace/contentimg/2019/08/01/2019080115162861479.jpg\" rel=\"noreferrer noopener\">https://p4.img.cctvpic.com/photoworkspace/contentimg/2019/08/01/2019080115162861479.jpg</a>\" <span class=\"html-attribute-name\">alt</span>=\"<span class=\"html-attribute-value\"> </span>\" <span class=\"html-attribute-name\">width</span>=\"<span class=\"html-attribute-value\">500</span>\" <span class=\"html-attribute-name\">isflag</span>=\"<span class=\"html-attribute-value\">1</span>\" /&gt;</span><span class=\"html-tag\">&lt;/p&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"125\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;p&gt;</span>深化供给侧结构性改革，要坚持稳中求进，以改革开放创造新供给、释放新需求。着力深挖国内需求潜力，拓展扩大最终需求，有效启动农村市场，多用改革办法扩大消费；要抓住有效投资的主要方向，把握节奏力度，夯实基础、补齐短板，积极扶持新产业、开拓新市场，不断培育产业新动能。<span class=\"html-tag\">&lt;/p&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"126\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;p&gt;</span>深化供给侧结构性改革，要着力深化体制机制改革，创造更好的市场环境、投资环境、创新环境，增添经济发展活力和动力。要继续腾笼换鸟，加快&amp;ldquo;僵尸企业&amp;rdquo;出清，加快淘汰落后产能，去芜存菁、大浪淘沙，给新经济发展留足空间；要推进金融供给侧结构性改革，大力提升金融资源的配置效率，着力加强对重点领域和薄弱环节的支持力度，引导金融机构增加对制造业、民营企业的中长期融资，推动金融对实体经济的支撑更加精准有效；要加快重大战略实施步伐，进一步推动京津冀协同发展、长江经济带发展、粤港澳大湾区建设、长三角区域一体化发展以及海南全面深化改革开放等区域协调发展战略，提升城市群功能。<span class=\"html-tag\">&lt;/p&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"127\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;p <span class=\"html-attribute-name\">style</span>=\"<span class=\"html-attribute-value\">text-align: center;</span>\"&gt;</span><span class=\"html-tag\">&lt;img <span class=\"html-attribute-name\">src</span>=\"<a class=\"html-attribute-value html-resource-link\" target=\"_blank\" href=\"https://p3.img.cctvpic.com/photoworkspace/contentimg/2019/08/01/2019080115163678094.jpg\" rel=\"noreferrer noopener\">https://p3.img.cctvpic.com/photoworkspace/contentimg/2019/08/01/2019080115163678094.jpg</a>\" <span class=\"html-attribute-name\">alt</span>=\"<span class=\"html-attribute-value\"> </span>\" <span class=\"html-attribute-name\">width</span>=\"<span class=\"html-attribute-value\">500</span>\" <span class=\"html-attribute-name\">isflag</span>=\"<span class=\"html-attribute-value\">1</span>\" /&gt;</span><span class=\"html-tag\">&lt;/p&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"128\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;p&gt;</span>深化供给侧结构性改革，要始终坚持以人民为中心的发展思想，着力做好民生工作，履行好保基本、保底线、保民生的兜底责任。要保持政策稳定性、连续性，把防止返贫放在重要位置，巩固拓展脱贫成果，彻底打赢脱贫攻坚战；就业是民生之本，要把稳就业摆在突出位置，实施好就业优先政策，做好高校毕业生、退役军人、农民工等重点群体就业工作；要保障市场供应和物价基本稳定，着力保障好群众的&amp;ldquo;米袋子&amp;rdquo;&amp;ldquo;菜篮子&amp;rdquo;&amp;ldquo;果盘子&amp;rdquo;&amp;ldquo;奶瓶子&amp;rdquo;，兜住民生保障的底线。<span class=\"html-tag\">&lt;/p&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"129\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;p <span class=\"html-attribute-name\">style</span>=\"<span class=\"html-attribute-value\">text-align: center;</span>\"&gt;</span><span class=\"html-tag\">&lt;img <span class=\"html-attribute-name\">src</span>=\"<a class=\"html-attribute-value html-resource-link\" target=\"_blank\" href=\"https://p2.img.cctvpic.com/photoworkspace/contentimg/2019/08/01/2019080115164366009.jpg\" rel=\"noreferrer noopener\">https://p2.img.cctvpic.com/photoworkspace/contentimg/2019/08/01/2019080115164366009.jpg</a>\" <span class=\"html-attribute-name\">alt</span>=\"<span class=\"html-attribute-value\"> </span>\" <span class=\"html-attribute-name\">width</span>=\"<span class=\"html-attribute-value\">500</span>\" <span class=\"html-attribute-name\">isflag</span>=\"<span class=\"html-attribute-value\">1</span>\" /&gt;</span><span class=\"html-tag\">&lt;/p&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"130\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;p&gt;</span>我国经济已由高速增长阶段转向高质量发展阶段，转变发展理念、优化经济结构、转换增长动力是一个长期复杂的过程，不会一蹴而就。我们坚信，有以习近平同志为核心的党中央的坚强领导，有全国人民的同心同德、齐心协力，我们一定能保持定力、坚定底气，坚持以改革的思路和办法办好自己的事，不断挖掘新潜能，开拓新局面，推动高质量发展取得新成就。<span class=\"html-tag\">&lt;/p&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"131\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;div <span class=\"html-attribute-name\">class</span>=\"<span class=\"html-attribute-value\">rbline1</span>\"&gt;</span>&amp;nbsp;<span class=\"html-tag\">&lt;/div&gt;</span> \r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t\t<tr>\r\n\t\t\t\t<td class=\"line-number\" value=\"132\">\r\n\t\t\t\t</td>\r\n\t\t\t\t<td class=\"line-content\">\r\n\t\t\t\t\t<span class=\"html-tag\">&lt;div <span class=\"html-attribute-name\">class</span>=\"<span class=\"html-attribute-value\">rbline2</span>\"&gt;</span>&amp;nbsp;<span class=\"html-tag\">&lt;/div&gt;</span>\r\n\t\t\t\t</td>\r\n\t\t\t</tr>\r\n\t\t</tbody>\r\n\t</table>\r\n</p>",
     *        "size_desc": "222222",
     *        "main_image_url": "http://192.168.1.133:8081/storage/uploads/image/2019/08/02/4b42a7565fb6d813c68fd7293f264274.jpeg",
     *        "main_video_url": null,
     *        "deleted_at": null,
     *        "created_at": "2019-08-02 01:37:57",
     *        "updated_at": "2019-08-02 01:56:23",
     *        "tree": [
     *            {
     *                "k": "颜色",
     *                "v": [
     *                    {
     *                        "id": 1,
     *                        "name": "红",
     *                        "imageUrl": null
     *                    },
     *                    {
     *                        "id": 2,
     *                        "name": "黄",
     *                        "imageUrl": null
     *                    },
     *                    {
     *                        "id": 3,
     *                        "name": "蓝",
     *                        "imageUrl": null
     *                    }
     *                ],
     *                "k_s": "s1"
     *            },
     *            {
     *                "k": "尺寸",
     *                "v": [
     *                    {
     *                        "id": 4,
     *                        "name": "大",
     *                        "imageUrl": null
     *                    },
     *                    {
     *                        "id": 5,
     *                        "name": "小",
     *                        "imageUrl": null
     *                    }
     *                ],
     *                "k_s": "s2"
     *            }
     *        ],
     *        "list": [
     *            {
     *                "id": 1001,
     *                "price": "100.00",
     *                "s1": 1,
     *                "s2": 4,
     *                "s3": null,
     *                "stock_num": 0
     *            },
     *            {
     *                "id": 1002,
     *                "price": "200.00",
     *                "s1": 2,
     *                "s2": 4,
     *                "s3": null,
     *                "stock_num": 0
     *            },
     *            {
     *                "id": 1003,
     *                "price": "300.00",
     *                "s1": 3,
     *                "s2": 4,
     *                "s3": null,
     *                "stock_num": 0
     *            }
     *        ],
     *        "list_images": [
     *            "http://192.168.1.133:8081/storage/uploads/image/2019/08/02/3819703491e6aa4bf27cc6f7277831e9.jpeg",
     *            "http://192.168.1.133:8081/storage/uploads/image/2019/08/02/2b096ee0d20d255413df51fd3c3dbb57.jpeg"
     *        ],
     *
     *       "detail_list_images": [
     *          "http://192.168.1.132:8081/storage/uploads/image/2019/08/10/iAF2Yar9lwDlGz3wz4UJLs1hbtHL2SvvTo42raPy.jpeg",
     *          "http://192.168.1.132:8081/storage/uploads/image/2019/08/10/oO11rnan8o3oMTahLwF5EmNriHy9AUXrnWYICanD.jpeg",
     *          "http://192.168.1.132:8081/storage/uploads/image/2019/08/10/mGkrxweII4dvWwtejsrpnkqUuOSkNAdl00FL3ThP.jpeg",
     *          "http://192.168.1.132:8081/storage/uploads/image/2019/08/10/dGTLyMb82Au2RYMnuqpWXJoLmPX3PzB5sxV8mFpf.jpeg",
     *          "http://192.168.1.132:8081/storage/uploads/image/2019/08/10/nlUhV2gmHpaWX4u3InX9OW240QBikBquc3O7EVdk.jpeg",
     *          "http://192.168.1.132:8081/storage/uploads/image/2019/08/10/J9tltgSRTm2o30xR30aYQ82jfE29wVA0UMqkOWJ5.png",
     *          "http://192.168.1.133:8081/storage/uploads/image/2019/08/09/mWZochSAnK8rYYfJSWKacflJcBQ5k8TZYvJd4KW7.jpeg",
     *          "http://192.168.1.133:8081/storage/uploads/image/2019/08/09/ILNWt6a2RJfG2hxS3y7KMGupixiM6x6EVRydzCdO.jpeg"
     *      ],
     *      "comments": [
     *            {
     *                "id": 1,
     *                "good_id": 98,
     *                "type_id": 1,
     *                "comment": "这是测试。。。。。12321",
     *                "name": "makete jsons",
     *                "phone": "188****1111",
     *                "star_scores": 5,
     *                "audited_at": "2019-09-03 16:47:28",
     *                "admin_user_id": 1,
     *                "deleted_at": null,
     *                "created_at": "2019-09-03 06:41:10",
     *                "updated_at": "2019-09-03 08:19:47",
     *                "comment_images": [
     *                    {
     *                        "id": 4,
     *                        "good_comment_id": 1,
     *                        "image_url": "http://192.168.1.132:8081/storage/uploads/image/2019/09/03/0d9b3b5fa25773bb9bf6a49ff44019c6.jpeg",
     *                        "created_at": "2019-09-03 06:46:52",
     *                        "updated_at": "2019-09-03 06:46:52"
     *                    }
     *                ]
     *            }
     *        ],
     *
     *    }
     *}
     */

    public function show(Request $request, $id){

        $good = Good::with(['comments' => function($query){
            $query->whereNotNull('audited_at')->orderBy('id', 'desc');
        },'comments.comment_images'])->where('id', $id)->first();

        if(!$good){
            return returned(false, '商品不存在');
        }

        //轮播图列表
        $list_images = $good->list_images()->pluck('image_url');

        //属性
        $attrs = collect([]);
        foreach ($good->good_attributes as $key=>$attribute){

            $key = $key+1;

            $item = [
                'k' => $attribute->show_name,
                'v' => $attribute->attribute_values()->select('attr_value_id as id','show_name as name','thumb_url as imgUrl')->get(),
                'k_s' => 's'.$key
            ];

            $attrs->push($item);
        }

        //sku list
        $skus = $good->skus()
            ->select('sku_id','price','s1','s2','s3','stock as stock_num')
            ->whereNull('disabled_at')
            ->get();

        $format_skus = collect([]);

        // dd($skus->count());

        if($skus->count() >0){

            $skus->map(function($sku) use($format_skus){

                $append = [
                    'price' =>  ($sku->price) * 100 ,
                    'id' => $sku->sku_id
                ];

                $sku = $sku->toArray();

                unset($sku['sku_id'], $sku['price']);

                $format_skus->push(array_merge($append, $sku));

            });

        }

        $good->tree = $attrs;
        if($attrs->count() == 0){
            $good->tree = null;
        }

        $good->none_sku = $good->tree ? false : true;

        $good->list = $format_skus;
        $good->list_images = $list_images;

        $good->collection_id = null;

        if($good->good_attributes->count() == 0 && $skus->count() >0){
            $good->collection_id = $skus->first()->id;
        }

        //货币符号
        $good->money_sign = config('money_sign');

        //商品总库存
        $good->stock_num = 99999;

        $detail_desc = $good->detail_desc;

        $match = preg_match_all('/http:[^\"]*/',$detail_desc, $matches);

        $good->detail_list_images = $matches[0];

        if(!$good->show_comment){
            unset($good->comments);
        }

        unset($good->good_attributes);

        return compact('good');

    }
}
