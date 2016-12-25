<?php

/**
 * Created by PhpStorm.
 * User: neojos
 * Date: 2016/12/22
 * Time: 22:53
 */
class PeopleController extends AbstractActivityAction
{
    public function __construct()
    {
        parent::__construct();
    }

    public function indexAction()
    {
        $people = $this->getParam("person");
        if (empty($people)) {
            $index = 0;
        } else {
            $index = intval($people);
        }

        $personDetail = $this->_getPeople($index);
        $shareContext = $this->setWechatShare($personDetail["shareContext"]["title"], $personDetail["shareContext"]["content"],
            $personDetail["shareContext"]["url"], $personDetail["shareContext"]["img"]);

        $this->_smarty->assign("navIndex", $index);
        $this->_smarty->assign("jsapi", $shareContext);
        $this->_smarty->assign("user", $personDetail);
        $this->_smarty->display('person/person.tpl');
    }

    private function _getPeople($index)
    {
        $personList = array(
            0 => array(
                "shareContext" => array(
                    "title" => "《2016，那些熟悉的陌生人》：90后女骑士",
                    "content" => "每一个外卖员背后，都藏有一个不为人知的故事。​",
                    "img" => "http://p3.ifengimg.com/a/2016/1225/fd7d2aa7805a1c6size123_w1260_h706.jpg",
                    "url" => "http://act.wetolink.com/people?person=0"
                ),
                "title" => "90后女骑士",
                "poster" => "http://p3.ifengimg.com/a/2016/1225/fd7d2aa7805a1c6size123_w1260_h706.jpg",
                "video" => "013361b5-69b0-4565-af88-58b5a6529e4c",
                "summary" => "这一年，互联网行业依旧风云变幻，不停上演着一幕又一幕精彩戏码，但有这样一群人，他们的工作周而复始，尽管平凡，却铸就了整个互联网。",
                "detail" => '<p><b>《2016那些熟悉的陌生人》之：90后的女骑士</b></p>

<p>这一年，我们变得越来越懒惰，早餐、午餐、晚餐以及宵夜，几乎所有的用餐需求都可以通过叫外卖来解决。</p>

<p>无论是大风、暴雨，还是雾霾，当每一份外卖送至眼前，我们的目光可能都聚焦在食物上，而没有注意到拎着食物的他们。</p>

<p>她叫王小凤，今年24岁，来自四川广元，是一名女外卖骑士，从事这份工作一年零4个月。</p>

<img src="http://p3.ifengimg.com/a/2016/1225/64266fcecd76525size98_w1265_h710.jpg" alt="" />

<p>“我的工作时间一般是从上午10点到下午2点，然后晚上5点到9点，夏天有时候会干到11点或12点才回家。”</p>

<p>因为工作时间都是饭点，所以，按时吃饭对他们来说，也是一种奢望。</p>

<p>送餐员这份工作，家人也并不是完全支持。</p>

<p>“他们觉得我年龄小，干这个工作天天骑着车很危险，但是，我的性格很适合这份工作，每天能见到很多人，所以我自己很喜欢现在的工作。”</p>

<p>不过，工作中也遇到过诸多苦恼。</p>

<p>有一次，她给一位顾客送餐，送到家门口怎么敲门都没人开，但却能听到里面传出的音乐声音，打了好几遍电话也没人接，没办法，她只好给顾客发信息说餐送到了家里没人，她等会再来送。</p>

<p>刚到楼下，顾客打电话过来，说刚才听音乐没注意手机，问她现在方便再送一下吗？</p>

<p>“好在我没走远，就又跑上楼给送了一趟，不然我只能先把别人的餐送完，再给他送了。”</p>

<p>有的时候，他们把餐送到了公司门口，顾客告知“稍等一下，马上就来。”可这一等，可能就是十几分钟。</p>

<p>被等待的顾客或许会心怀感激，但送餐员接下来要面临的，可能就是“送餐太慢”的抱怨。</p>

<p>“有的时候送完餐，顾客说声谢谢，我们的心理都会很温暖。有的时候看到前一天顾客给自己评的五星，我真会觉得这份工作越干越有劲。”</p>
<img src="http://p3.ifengimg.com/a/2016/1225/6fd75b18206ea21size90_w1259_h711.jpg" alt="" />

<p>看到这，你可能会觉得这只是一位普通送餐员的日常工作，但了解王小凤背后的故事后，或许更能让我们对平凡力量心生敬畏。</p>

<p>92年出生的她，已经是一位6岁孩子的母亲，她的丈夫去年患上尿毒症，这也迫使她独自一人北上，扛起了整个家庭。</p>

<p>“我跟我老公的感情非常好，所以我希望多赚一些钱，帮他看病。”</p>

<p>她把每个月绝大部分的工资都寄回家里，给老公和孩子，自己在北京生活的非常拮据。</p>

<p>“有时候工作停下来，也会觉得自己很辛苦，但是想到有人可能比自己还困难，都能熬过来，自己为什么不能坚持下去？”</p>

<p>这样一坚持，就是一年多，而付出也得到了回报。</p>

<p>“这一年虽然很辛苦，但基本上自己赚的钱能补上家里的开销，所以再苦也是值得的。”</p>
<img src="http://p3.ifengimg.com/a/2016/1225/f60944859c0f6eesize121_w1261_h710.jpg" alt="" />
<p>她从来没有对身边人说过自己的家庭，她觉得终究是要靠自己的双手和劳动成果，靠自己的坚强来帮助家庭渡过难关。</p>

<p>对于2017年，她也许下了自己的心愿。

<p>“我希望能多赚一些钱，让老公好起来，尽量多陪我们几年，陪着我一起看着儿子成长，一起开开心心每一天。”</p>
<img src="http://p3.ifengimg.com/a/2016/1225/8652f78a0783111size102_w1266_h709.jpg" alt="" />
<p><b>后记:</b>

<p>当被问及如何看待互联网对生活带来的影响时，她反问了一句，“啥网”？</p>

<p>的确，“互联网”这个词对她来说可能很陌生。</p>

<p>她可能也不知道，她所从事的工作这一年给互联网行业带来了多大的影响，正如我们也不知道，每一份外卖背后都藏有一个不为人知的故事。</p>'
            )
        );

        return $personList[$index];
    }
}