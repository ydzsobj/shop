<?php

namespace App\Http\Controllers\User;

use App\Models\Good;
use App\Models\GoodModule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
     * {
     *    "success": true,
     *    "msg": "",
     *    "data": {
     *        "current_page": 1,
     *        "data": [
     *            {
     *                "id": 45,
     *                "title": "测试0807",
     *                "original_price": "999.00",
     *                "price": "$888.00",
     *                "good_module_id": 2,
     *                "main_image_url": "http://192.168.1.133:8081/storage/uploads/image/2019/08/08/c10410f41dcf17266e42e9a1de5cd262.png"
     *            },
     *            {
     *                "id": 44,
     *                "title": "测试0807",
     *                "original_price": "999.00",
     *                "price": "$888.00",
     *                "good_module_id": 2,
     *                "main_image_url": "http://192.168.1.133:8081/storage/uploads/image/2019/08/08/0ca6091f438822002b53a6a29617e078.png"
     *            },
     *            {
     *                "id": 43,
     *                "title": "122222",
     *                "original_price": "111.00",
     *                "price": "$111.00",
     *                "good_module_id": 2,
     *                "main_image_url": "http://192.168.1.133:8081/storage/uploads/image/2019/08/07/8e599aec96ca117adb8f0227047819e1.png"
     *            },
     *            {
     *                "id": 42,
     *                "title": "联想ThinkPad X1 Carbon 2018（05CD）英特尔酷睿i7 14英寸轻薄笔记本电脑（i7-8550U 16G 1TSSD WQHD Win10Pro）黑色",
     *                "original_price": "999.00",
     *                "price": "$888.00",
     *                "good_module_id": 1,
     *                "main_image_url": "http://192.168.1.133:8081/storage/uploads/image/2019/08/07/f6cfcfdb53d78856bc8f5d78e97f7a89.png"
     *            },
     *            {
     *                "id": 41,
     *                "title": "联想ThinkPad X1 Carbon 2018（05CD）英特尔酷睿i7 14英寸轻薄笔记本电脑（i7-8550U 16G 1TSSD WQHD Win10Pro）黑色",
     *                "original_price": "99999.00",
     *                "price": "$88888.00",
     *                "good_module_id": 4,
     *                "main_image_url": "http://192.168.1.133:8081/storage/uploads/image/2019/08/07/4f21e882d657388dbb10c1aadb5d9a77.png"
     *            },
     *        ],
     *        "first_page_url": "http://192.168.1.133:8081/api/user/goods?page=1",
     *        "from": 1,
     *        "last_page": 2,
     *        "last_page_url": "http://192.168.1.133:8081/api/user/goods?page=2",
     *        "next_page_url": "http://192.168.1.133:8081/api/user/goods?page=2",
     *        "path": "http://192.168.1.133:8081/api/user/goods",
     *        "per_page": 20,
     *        "prev_page_url": null,
     *        "to": 20,
     *        "total": 22
     *    }
     *}
     */
    public function index(Request $request){

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
     * @apiSuccess {String} price 实际价格
     * @apiSuccess {Array} pay_types 付款方式
     * @apiSuccess {Number} show_comment 是否显示评论模块
     * @apiSuccess {string} detail_desc 商品详情
     * @apiSuccess {string} size_desc 尺寸描述
     * @apiSuccess {string} main_image_url 封面主图
     * @apiSuccess {string} main_video_url 视频地址
     * @apiSuccess {array} list_images 轮播图地址
     * @apiSuccess {array} tree 商品属性规格
     * @apiSuccess {array} list 商品skus
     *
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *{
     *    "good": {
     *        "id": 24,
     *        "title": "乔丹男鞋2019夏新款跑鞋男网面透气休闲鞋子轻便时尚跑步鞋运动鞋男 曜石黑231 44.5",
     *        "name": "08020937-运动鞋",
     *        "original_price": "111.00",
     *        "price": "99.00",
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
     *        ]
     *    }
     *}
     */

    public function show(Request $request, $id){

        $good = Good::find($id);

        //轮播图列表
        $list_images = $good->list_images()->pluck('image_url');

        //属性
        $attrs = collect([]);
        foreach ($good->attributes as $key=>$attribute){

            $key = $key+1;

            $item = [
                'k' => $attribute->name,
                'v' => $attribute->attribute_values()->select('remote_id as id','name','thumb_url as imgUrl')->get(),
                'k_s' => 's'.$key
            ];

            $attrs->push($item);
        }

        //sku list
        $skus = $good->skus()
            ->select('sku_id as id','price','s1','s2','s3','stock as stock_num')
            ->whereNull('disabled_at')
            ->get();
        $skus = $skus->map(function($sku){
            $sku->price =  ($sku->price) * 100;
            return $sku;
        });

        $good->tree = $attrs;
        if($attrs->count() == 0 || $skus->count() ==0){
            $good->tree = null;
        }

//        dd($good->tree);
        $good->none_sku = $good->tree ? false : true;

        $good->list = $skus;
        $good->list_images = $list_images;

        $good->collection_id = null;

        if($good->attributes->count() == 0){
            $good->collection_id = $skus->first()->id;
        }

        //货币符号
        $good->money_sign = config('money_sign');

        //商品总库存
        $good->stock_num = 99999;

        unset($good->attributes);

        return compact('good');

    }
}
