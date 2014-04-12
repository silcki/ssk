<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet SYSTEM "symbols.ent">
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template name="callback_forAll">		
	<form action="/index/order/" id="order_service" method="post" enctype="multipart/form-data">							
								<div id="zakaz" class="feedback">
									<ul class="errhold"></ul>
									<fieldset title="Укажите удобные для Вас средства связи, и наши менеджеры обязательно свяжутся в самые короткие сроки ">
										<legend>Заказ услуги</legend>
										<!--<div style="color:#717171;">Комментарии, список ключевых слов, описание вашего бизнеса:</div>-->
										<div class="input">
											<label for="servicename">Ваше имя<i class="zvezda">*</i></label>
											<input type="text" id="servicename" class="inputtxt" name="name"/>
										</div>
										<div class="input">
											<label for="servicephone">Телефон<i class="zvezda">*</i></label>
											<input type="text" id="servicephone" class="inputtxt" name="phone"/>
										</div>
										<div class="input">
											<label for="serviceemail">E-mail<i class="zvezda">*</i></label>
											<input type="text" id="serviceemail" class="inputtxt" name="email"/>
										</div>
									</fieldset>
									<div class="hidening">
										<fieldset>
											<div class="dopcontacts"> <a class="dopadd" href="#">+ ещё контакты</a>
												<div class="dopcont close">
													<div class="input">
														<label for="skype">Скайп</label>
														<input type="text" id="skype" class="inputtxt" name="skype"/>
													</div>
													<div class="input">
														<label for="icq">ICQ</label>
														<input type="text" id="icq" class="inputtxt" name="icq"/>
													</div>
													<div class="input">
														<label for="gtalk">Гуглтолк</label>
														<input type="text" id="gtalk" class="inputtxt" name="google_talk"/>
													</div>
												</div>
											</div>
										</fieldset>
										<xsl:if test="count(service_name) &gt; 0">
											<fieldset>
												<legend>Я хочу услугу:</legend>
												<xsl:apply-templates select="service_name"/>
												<!--<label for="service_name_id" class="error">Поле является обязательным</label>-->
											</fieldset>
										</xsl:if>											
										<xsl:if test="count(service_price) &gt; 0">
											<fieldset>
												<legend>Планирую вложить:</legend>
												<xsl:apply-templates select="service_price"/>
												<i class="zvezda">*</i><label class="error">Поле является обязательным</label>
											</fieldset>
										</xsl:if>
										<fieldset>
											<legend>Добавить файл:</legend>
											<input type="file" size="28" name="order_attach"/>
										</fieldset>									
										<fieldset>
											<legend>Комментарий к заказу:</legend>
											<div class="textarea">
												<textarea cols="27" rows="7" name="description"></textarea>
											</div>
										</fieldset>
									</div>
									<button class="btnh"><p class="btn"><a href="/index/order/" class="send">Отправить заказ<em></em></a></p></button>
									<div style="color:#717171;">Мы проанализируем ваш запрос и свяжемся с вами в течение рабочего дня.</div>
								</div>
							</form>
	</xsl:template>



</xsl:stylesheet>