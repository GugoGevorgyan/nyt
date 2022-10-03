<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Договор аренды № {{ $contract->driver_contract_id }}</title>
  <style>
    body { font-family: DejaVu Sans }
    table, td, th {
      border: 1px solid black;
      border-spacing: 0px;
      padding: 0px;
    }
  </style>
</head>
<body>
<script type="text/php">
    if (isset($pdf)) {
        $x = 570;
        $y = 750;
        $text = "{PAGE_NUM}";
        $font = null;
        $size = 14;
        $color = array(0,0,0);
        $word_space = 0.0;  //  default
        $char_space = 0.0;  //  default
        $angle = 0.0;   //  default
        $pdf->page_text($x, $y, $text, $font, $size, $color, $word_space, $char_space, $angle);
    }
</script>
<h2 style="text-align: center">Договор аренды № {{ $contract->driver_contract_id }}</h2>
<table style="width: 100%; border: none;">
  <tr>
    <td style="border: none;">г. {{$entity->city? $entity->city->name: ''}}</td>
    <td style="border: none; text-align: right">{{date('«d» F Y', strtotime($contract->signing_day))}}года</td>
  </tr>
</table>
<p>
  {{$entity->type? $entity->type->name: ''}}  «{{$entity->name}}» именуемое в дальнейшем «Арендодатель», в лице Директора Департамента Управления персоналом {{$worker->surname}}
    {{$worker->name}} {{$worker->patronymic}}, действующего на основании Доверенности от {{date('«d» F Y', strtotime($contract->signing_day))}}г., с одной стороны, и гражданин {{$info->citizen}}<b> {{$info->surname}} {{$info->name}} {{$info->patronymic}}, </b>паспорт:
  <b>серии {{$info->passport_serial.' №'.$info->passport_number}},</b> (кем, когда выдан) <b>{{$info->passport_issued_by}},
    {{date('d-m-Y', strtotime($info->passport_when_issued))}}г., проживающий по адресу: {{$info->address}}</b> именуемый в дальнейшем «Арендатор», с другой стороны, совместно именуемые в дальнейшем
  «Стороны», заключили настоящий Договор (далее «Договор») о нижеследующем:
</p>
<h4 style="text-align: center;">1. ПРИМЕНИМЫЕ ТЕРМИНЫ</h4>
<p>1.1. Для целей настоящего Договора нижеприведенные термины, если контекст и содержание настоящего Договора  не требуют иного, имеют следующее значение:
<strong>Транспортное средство (либо ТС)</strong> - означает автотранспортное средство, имеющее следующие основные идентифицирующие признаки:
  <ul>
    <li>Марка: <strong>{{$car->mark}} {{$car->model}}</strong>;</li>
    <li>Государственный номерной знак: <strong>{{$car->state_license_plate}}</strong>;</li>
    <li>Идентификационный номер (VIN): <strong>{{$car->vin_code}}</strong></li>
    <li>Номер кузова: <strong>{{$car->body_number}}</strong></li>
    <li>Год выпуска: <strong>{{date('Y', strtotime($car->year))}}</strong></li>
    <li>Цвет: <strong>{{$car->color}}</strong></li>
    <li>Свидетельство о регистрации ТС Серия <strong>{{$car->registration_number}}</strong>, выдано <strong>{{$car->registration_date}}</strong></li>
    <li>Паспорт ТС Серия <strong>{{$car->vehicle_licence_number}}</strong>, выдан <strong>{{$car->vehicle_licence_date}}</strong></li>
  </ul>
  и предоставляемое Арендодателем Арендатору во временное владение и пользование для личных целей, не связанных с деятельностью в качестве такси и не связанных с иной предпринимательской деятельностью, в порядке и на условиях, предусмотренных настоящим Договором.
</p>
<br>
<br>
<h4 style="text-align: center;">2. ПРЕДМЕТ ДОГОВОРА</h4>
<p>2.1. По настоящему Договору Арендодатель предоставляет Арендатору Транспортное средство за плату во временное владение и пользование для личных целей, не связанных с деятельностью в качестве такси и не связанных с иной предпринимательской деятельностью, в порядке и на условиях, предусмотренных настоящим договором.</p>
<h4 style="text-align: center;">3. ПРАВА И ОБЯЗАННОСТИ СТОРОН</h4>
<h5>3.1. Обязанности Арендодателя.</h5>
<p>3.1.1. Арендодатель обязуется:</p>
<p>3.1.1.1. Передать Арендатору Транспортное средство во временное владение и пользование по акту приема-передачи, форма которого установлена в Приложении №1 к настоящему Договору, в исправном состоянии, готовом к эксплуатации, а так же предоставить регистрационные и иные документы, необходимые для использования Арендатором Транспортного средства;</p>
<p>3.1.1.2. Обеспечить Арендатора необходимой технической документацией, регламентирующей порядок эксплуатации Транспортного средства;</p>
<p>3.1.1.3. Страховать за свой счёт Транспортное средство по полису обязательного страхования гражданской ответственности владельцев транспортных средств, уплачивать транспортный налог, предоставлять Арендатору за его счет мойку по льготным ценам.</p>
<h5>3.2. Права Арендодателя</h5>
<p>3.2.1. Арендодатель вправе:</p>
<p>3.2.1.1.Осуществлять контроль, за порядком использования Транспортного средства,  а также за обеспечением его сохранности. </p>
<p>3.2.1.2. Расторгнуть настоящий договор  в одностороннем порядке предупредив Арендатора за 14 дней в случаях наличия задолженности Арендатора перед Арендодателем и нарушения Арендатором условий настоящего договора.</p>
<h5>3.3. Обязанности Арендатора.</h5>
<p>3.3.1. Арендатор обязуется:</p>
<p>3.3.1.1. Принять Транспортное средство регистрационные и иные документы, необходимые Арендатору для использования Транспортного средства по акту приема-передачи, форма которого установлена Приложением № 2 к настоящему Договору;</p>
<p>3.3.1.2. Управлять Транспортным средством и осуществлять его эксплуатацию самостоятельно, не передавать Транспортное средство третьим лицам, за исключением случаев прямо предусмотренных настоящим Договором;</p>
<p>3.3.1.3. Обеспечить надлежащую эксплуатацию Транспортного средства, а именно:
  <ul>
    <li>при эксплуатации Транспортного средства соблюдать законодательство Российской Федерации и нормативные акты государственных и муниципальных органов власти и управления, в том числе  Правила дорожного движения, утвержденные Постановлением Совета министров - Правительства РФ от 23.10.1993 г. № 1090;</li>
    <li>осуществлять эксплуатацию Транспортного средства исключительно в соответствии с настоящим Договором; </li>
    <li>не производить дооборудование Транспортного средства (не устанавливать на Транспортном средстве дополнительное оборудование) без согласования с Арендодателем;</li>
  </ul>
</p>
<p>3.3.1.4. Обеспечить сохранность и комплектность полученного Транспортного средства;</p>
<p>3.3.1.5. Обеспечить сохранность переданных Арендодателем регистрационных и иных документов, необходимых Арендатору для использования Транспортного средства;</p>
<p>3.3.1.6. Обеспечивать безопасную эксплуатацию Транспортного средства;</p>
<p>3.3.1.7. Надлежащим образом следить за внешним видом и техническим состоянием Транспортного средства.</p>
<p>3.3.1.8. Один раз в день производить мойку транспортного средства за свой счет;</p>
<p>3.3.1.9. В случае выявления неисправности Транспортного средства незамедлительно обеспечить доставку автомобиля в ремонтную зону Арендодателя для ремонта;</p>
<p>3.3.1.10. Для своевременного прохождения планового государственного технического осмотра, размещения на транспортном средстве рекламных изображений, а также в иных случаях  предоставить Транспортное средство лицу, указанному Арендодателем, по адресу и во время,  указанные Арендодателем;</p>
<p>3.3.1.11. Незамедлительно информировать Арендодателя:
  <ul>
    <li>о любом происшествии с Транспортным средством, в результате которого Транспортное средство (любая его составляющая) было уничтожено, повреждено, похищено, а так же сообщить о нанесенном Транспортному средству ущербе, возможности осуществить ремонт или восстановить Транспортное средство;</li>
    <li>о любом происшествии, результатом которого явилось причинение вреда третьим лицам Транспортным средством, его механизмами, устройствами, оборудованием.</li>
  </ul>
</p>
<p>3.3.1.12. Оформить все необходимые, по мнению Арендодателя, документы, а также совершить все необходимые, по мнению Арендодателя, действия в случае наступления страхового случая, по договорам страхования, заключенным Арендодателем в соответствии с настоящим Договором,  в том числе, в случае возникновения ДТП, Арендатор вне зависимости от его вины обязуется оформить документы в органах ГИБДД, заполнить Извещение о ДТП и передать весь пакет документов в службу безопасности движения Арендодателя для предоставления в страховую Арендодателя;</p>
<p>3.3.1.13. Следить за  сохранностью размещенных на Транспортном средстве рекламных и/или фирменных изображений, а в случае их повреждения либо утраты уведомить об этом Арендодателя в тот же день и незамедлительно предоставить Арендодателя Транспортное средство для размещения новых изображений с компенсацией ущерба;</p>
<p>3.3.1.14. При управлении Транспортным средством иметь при себе:
  <ul>
    <li>водительское удостоверение;</li>
    <li>свидетельство о регистрации ТС;</li>
    <li>доверенность на управление ТС либо договор аренды Транспортного средства;</li>
    <li>полис ОСАГО;</li>
    <li>документ о государственном техническом осмотре, а также иные документы, указанные Арендодателем;</li>
  </ul>
</p>
<p>3.3.1.15. Не передавать транспортное средство в субаренду иным лицам.</p>
<h5>3.4. Права Арендатора.</h5>
<p>3.4.1. Использовать транспортное средство для личных целей, не связанных с деятельностью в качестве такси и не связанных с иной предпринимательской деятельностью.</p>
<p>3.4.2. Расторгнуть настоящий договор в одностороннем порядке в случае отсутствия задолженности Арендатора перед Арендодателем предупредив Арендодателя за 14 дней. В данный срок договор считается действующим и за него уплаченная арендная плата Арендатору не возвращается.</p>
<h4 style="text-align: center;">4. РАСЧЕТЫ</h4>
<p>4.1. Арендатор ежедневно по 6 000 (Шесть тысяч) рублей в день выплачивает Арендодателю арендную плату за временное владение и пользование Транспортным средством безналичным перечислением или наличными в кассу.</p>
<p>4.2. В случае если имеется задолженность Арендатора по арендной плате, Арендодатель имеет право в одностороннем порядке расторгнуть настоящий договор.</p>
<p>4.3. В случае нарушения правил дорожного движения Арендатором, Арендатор выплачивает Арендодателю сумму наложенного штрафа, учитывая комиссию по его уплате.</p>
<h4 style="text-align: center;">5. ОТВЕТСТВЕННОСТЬ СТОРОН</h4>
<p>5.1. В случае гибели или повреждения Транспортного средства Арендатор обязан возместить Арендодателю причиненные убытки в соответствии с действующим законодательством РФ.</p>
<p>5.2. Арендатор несет ответственность за вред, причиненный третьим лицам Транспортным средством, его механизмами, устройствами, оборудованием.</p>
<p>При этом если вред, причиненный третьим лицам Транспортным средством, его механизмами, устройствами, оборудованием признается страховым случаем в соответствии с договором обязательного страхования гражданской ответственности владельцев транспортных средств, но страхового возмещения по указанному договору страхования недостаточно для того, чтобы полностью возместить вред, Арендатор возмещает третьим лицам разницу между страховым возмещением и фактическим размером ущерба.</p>
<p>В случае, если в соответствии с действующим законодательством на Арендодателя будет возложена обязанность возместить третьим лицам вред, причиненный Арендатором, Арендодатель имеет право обратного требования (регресса) к Арендатору в размере выплаченного возмещения.</p>
<p>5.3. В случае неисполнения или ненадлежащего исполнения Арендатором обязательств по возврату Транспортного средства и (или) регистрационных и (или) иных документов, переданных Арендодателем, при досрочном расторжении настоящего договора или его окончании, Арендатор обязуется уплатить Арендодателя штраф в размере 10 000 (Десяти тысяч) рублей в день, а также возместить Арендодателя причиненные убытки в течение одного дня с момента предъявления Арендодателем соответствующих требований, при этом указанные убытки могут быть взысканы Арендодателем сверх указанного штрафа.</p>
<p>5.4. В случае неисполнения или ненадлежащего исполнения Арендатором обязательств по обеспечению сохранности свидетельства о регистрации ТС, полиса ОСАГО и иных документов, в том числе в случае их утраты, Арендатор обязуется возместить Арендодателя стоимость восстановления каждого из них и уплатить Арендодателя штраф в размере равном дневной арендной плате за каждый день простоя Транспортного средства в результате их утраты в течение одного дня с момента предъявления Арендодателем соответствующего требования.</p>
<p>5.5. В случае не предоставления транспортного средства на государственный технический осмотр Арендатор уплачивает Арендодателя штраф в размере 3000 (три тысячи) рублей за каждый случай такого нарушения.</p>
<p>5.6. В случае ненадлежащего внешнего вида транспортного средства или ненадлежащего технического состояния транспортного средства, по мнению Арендодателя, Арендатор уплачивает штраф в размере 5 000 (пять тысяч) рублей за каждый случай такого нарушения.</p>
<p>5.7. В случае нарушения сроков уплаты арендной платы Арендатор уплачивает Арендодателю штраф в размере 10 000 (десять тысяч) рублей за каждый случай такого нарушения.</p>
<h4 style="text-align: center;">6. ИНСПЕКТИРОВАНИЕ И КОНТРОЛЬ</h4>
<p>6.1. Арендатор обязан обеспечить условия для инспектирования и контроля Транспортного средства со стороны Арендодателя. При этом Арендатор обязан направлять транспортное средство в место, указанное Арендодателем, не реже одного раза в пять дней.</p>
<p>6.2. Арендодатель, в лице своих сотрудников или иных лиц, наделенных Арендодателем соответствующими полномочиями, вправе в любое разумное время осуществлять контроль за состоянием Транспортного средства, порядком и условиями его использования, проводить осмотры и проверять техническое состояние Транспортного средства, и осуществлять все иные необходимые действия.</p>
<p>При этом  Арендодатель не осуществляет контроль за безопасностью эксплуатации Арендатором Транспортного средства. </p>
<h4 style="text-align: center;">7. СРОК АРЕНДЫ И ПОРЯДОК РАСТОРЖЕНИЯ НАСТОЯЩЕГО ДОГОВОРА</h4>
<p>7.1. По настоящему договору срок аренды устанавливается в размере одного года с момента вступления в силу настоящего договора.</p>
<p>7.2. Настоящий договор вступает в силу с момента его подписания Сторонами и действует до полного исполнения обязательств Сторонами.</p>
<p>7.3. Настоящий договор может быть досрочно расторгнут по соглашению Сторон или по инициативе Арендодателя в одностороннем порядке при нарушении Арендатором любых условий настоящего договора.</p>
<h4 style="text-align: center;">8. ОБСТОЯТЕЛЬСТВА НЕПРЕОДОЛИМОЙ СИЛЫ</h4>
<p>8.1. Стороны освобождаются от ответственности за частичное или полное неисполнение обязательств по настоящему Договору, если это неисполнение явилось следствием обстоятельств непреодолимой силы, возникших после заключения настоящего Договора, в результате таких событий чрезвычайного характера, которые Стороны не могли предвидеть или предотвратить разумными мерами. К обстоятельствам непреодолимой силы относятся события, на которые Стороны не могут оказать влияние и за возникновение которых они не несут ответственности, в том числе землетрясение, наводнение, иные стихийные бедствия, а также забастовка, военные действия, правовые акты органов государственной власти.</p>
<p>8.2. Сторона, для которой создалась невозможность исполнения обязательств по настоящему Договору, обязана известить в письменной форме другую Сторону о наступлении и прекращении вышеуказанных обстоятельств непреодолимой силы не позднее 5 (пяти) дней с момента их наступления.</p>
<p>8.3. Надлежащим доказательством наличия указанных выше обстоятельств непреодолимой силы и их продолжительности будут служить свидетельства и/или официальные подтверждение соответствующих компетентных государственных органов.</p>
<p>8.4. В случае если обстоятельства непреодолимой силы будут длиться более 60 (шестьдесят) дней, любая из Сторон вправе отказаться от исполнения настоящего Договора в одностороннем порядке путем направления другой Стороне уведомления с приложением доказательств. При этом настоящий Договор будет считаться расторгнутым с даты получения вышеуказанного уведомления.</p>
<h4 style="text-align: center;">9. РАЗРЕШЕНИЕ СПОРОВ</h4>
<p>9.1. Все разногласия и споры, возникающие при исполнении настоящего Договора или в связи с ним, урегулируются, по возможности, посредством переговоров между Сторонами.</p>
<p>9.2. При невозможности разрешения споров, возникших между Сторонами при исполнении настоящего Договора или в связи с ним, посредством переговоров, такие споры  подлежат передаче на рассмотрение районного суда г. Москвы по выбору Арендодателя в порядке, предусмотренном действующим законодательством Российской Федерации.</p>
<h4 style="text-align: center;">10. ЗАКЛЮЧИТЕЛЬНЫЕ ПОЛОЖЕНИЯ</h4>
<p>10.1. Настоящий Договор и документы, указанные в нем, в полной мере отражают договоренности, достигнутые Сторонами по настоящему Договору, и заменяют собой все предыдущие соглашения и договоренности по предмету настоящего Договора.</p>
<p>10.2. Изменения и дополнения к настоящему Договору имеют юридическую силу в случае, если они составлены в письменном виде и подписаны Сторонами или должным образом уполномоченными представителями Сторон, за исключением случая досрочного расторжения в одностороннем порядке по инициативе Арендодателя.</p>
<p>10.3. Настоящий Договор составлен в двух подлинных экземплярах, имеющих одинаковую юридическую силу, по одному для каждой из Сторон.</p>
<p>10.4. Все приложения к настоящему Договору являются неотъемлемой его частью.</p>
<p>10.5. Во всем, что не урегулировано настоящим Договором, Стороны руководствуются действующим законодательством РФ.</p>
<div style="page-break-before: always;">
<h4 style="text-align: center;">11. ПОДПИСИ И РЕКВИЗИТЫ СТОРОН</h4>
<table>
  <tr>
    <th style="text-align: center;">Принципал</th>
    <th style="text-align: center;">Агент</th>
  </tr>
  <tr>
    <td valign="top" style="padding: 10px; border-bottom: 0; padding-bottom: 50px;">
      {{$entity->type? $entity->type->abbreviation: ''}} «{{$entity->name}}» <br>
      Юридический адрес: {{$entity->zip_code}}, г.{{$entity->city? $entity->city->name: ''}}, {{$entity->address}} <br>
      Контактный телефон : {{$entity->phone}}<br>
      Фактический адрес: {{$entity->zip_code}}, г.{{$entity->city? $entity->city->name: ''}}, {{$entity->address}} <br>
      Контактный телефон : {{$entity->phone}} <br>
      ИНН/КПП: {{$entity->tax_inn}}/{{$entity->tax_kpp}} <br>
      ОГРН: {{$entity->tax_psrn}} от {{date('d.m.Y', strtotime($entity->tax_psrn_date))}}
    </td>
    <td valign="top" style="padding: 10px; border-bottom: 0; padding-bottom: 50px;">
      <b>{{$info->surname}} {{$info->name}} {{$info->patronymic}}</b>, <br>
      паспорт: <b>серии {{$info->passport_serial.' №'.$info->passport_number}}, <br>
      </b> (кем, когда выдан) <b>{{$info->passport_issued_by}}, {{date('d-m-Y', strtotime($info->passport_when_issued))}}г.</b>,<br>
      проживающий по адресу: <b>{{$info->address}}</b>
    </td>
  </tr>
  <tr>
    <td style="padding: 10px; border-top: 0;border-bottom: 0">Директор Департамента Управления персоналом</td>
    <td style="padding: 10px; border-top: 0;border-bottom: 0"></td>
  </tr>
  <tr>
    <td style="padding: 10px; border-top: 0;">
      <table style="border:none;" width="100%">
        <tr>
          <td valign="bottom" style="width: 60%; border: none;">
            <div style="height: 20px; border-bottom: 1px solid"></div>
            <div style="text-align: center;"><span>(подпись)</span></div>
          </td>
          <td valign="top" style="border: none; text-align: center;">{{substr($worker->name, 0, 1)}}. {{substr($worker->patronymic, 0, 1)}}. {{$worker->surname}}</td>
        </tr>
      </table>
    </td>
    <td style="padding: 10px; border-top: 0;">
      <table style="border:none;" width="100%">
        <tr>
          <td valign="bottom" style="width: 60%; border:none;">
            <div style="height: 20px; border-bottom: 1px solid"></div>
            <div style="text-align: center;"><span>(подпись)</span></div>
          </td>
          <td valign="top" style="border:none; text-align: center;">{{substr($info->name, 0, 1)}}. {{substr($info->patronymic, 0, 1)}}. {{$info->surname}}</td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</div>
<div style="page-break-before: always;">
<table style="border:none; width: 100%">
  <tr>
    <td style="border:none; width: 60%"></td>
    <td style="border:none; text-align: center;">
    Приложение №1<br>
    К Договору аренды № {{$contract->driver_contract_id}}/ {{$entity->name}}<br>
    От {{date('«d» F Y', strtotime($contract->signing_day))}} г.
    </td>
  </tr>
</table>
</div>
<h4 style="text-align: center;">Акт приема-передачи транспортного средства</h4>
<table style="border:none; width: 100%">
  <tr>
    <td style="border:none;">г. {{$entity->city? $entity->city->name: ''}}</td>
    <td style="border: none; text-align: right">{{date('«d» F Y', strtotime($contract->signing_day))}}</td>
  </tr>
</table>
<p>{{$entity->type? $entity->type->name: ''}}  «{{$entity->name}}» именуемое в дальнейшем «Арендодатель», в лице Директора Департамента Управления персоналом {{$worker->surname}}
    {{$worker->name}} {{$worker->patronymic}}, действующего на основании Доверенности от {{date('«d» F Y', strtotime($contract->signing_day))}}г., с одной стороны, и гражданин {{$info->citizen}}
  <b>{{$info->surname}} {{$info->name}} {{$info->patronymic}}, </b>паспорт:
  <b>серии {{$info->passport_serial.' №'.$info->passport_number}},</b> (кем, когда выдан) <b>{{$info->passport_issued_by}},
  {{date('d-m-Y', strtotime($info->passport_when_issued))}}г., проживающий по адресу: {{$info->address}}</b>, именуемый в дальнейшем «Арендатор», с другой стороны, совместно именуемые в дальнейшем «Стороны», заключили настоящий Договор (далее «Договор») о нижеследующем:
</p>
<ol>
  <li>Арендатор принял, а Арендодатель передал во временное владение и пользование следующее транспортное средство:
    <table style="width: 100%; font-size: 12px">
      <tr>
        <td style="text-align: center;">Марка автомобиля</td>
        <td style="text-align: center;">Государственный регистрационный номер</td>
        <td style="text-align: center;">Год выпуска</td>
        <td style="text-align: center;">VIN</td>
        <td style="text-align: center;">Номер двигателя</td>
        <td style="text-align: center;">СТС</td>
        <td style="text-align: center;">Цвет кузова</td>
      </tr>
      <tr>
        <td style="text-align: center;"><strong>{{$car->mark}} {{$car->model}}</strong></td>
        <td style="text-align: center;"><strong>{{$car->state_license_plate}}</strong></td>
        <td style="text-align: center;"><strong>{{date('Y', strtotime($car->year))}}</strong></td>
        <td style="text-align: center;"><strong>{{$car->vin_code}}</strong></td>
        <td style="text-align: center;"><strong>{{$car->engine_number}}</strong></td>
        <td style="text-align: center;"><strong>{{$car->vehicle_licence_number}}</strong></td>
        <td style="text-align: center;"><strong>{{$car->color}}</strong></td>
      </tr>
    </table>
    Техническое состояние транспортное средство исправное, готово к эксплуатации.
  </li>
  <li>Арендатор принял, а Арендодатель передал во временное владение и пользование следующие документы:</li>
</ol>
<table style="width: 100%;">
  <tr>
    <th style="text-align: center;">Арендодатель</th>
    <th style="text-align: center;">Арендатор</th>
  </tr>
  <tr>
    <td valign="bottom" style="padding: 10px; border-top: 0">
      Директор Департамента Управления персоналом
      <table style="border:none;" width="100%">
        <tr>
          <td style="width: 60%;border:none;">
            <div style="height: 20px; border-bottom: 1px solid"></div>
            <div style="text-align: center;"><span>(подпись)</span></div>
          </td>
          <td valign="top" style="border:none;text-align: center;">{{substr($worker->name, 0, 1)}}. {{substr($worker->patronymic, 0, 1)}}. {{$worker->surname}}</td>
        </tr>
      </table>
    </td>
    <td valign="bottom" style="padding: 10px; border-top: 0">
      <table style="border:none;" width="100%">
        <tr>
          <td style="width: 60%;border:none;">
            <div style="height: 20px; border-bottom: 1px solid"></div>
            <div style="text-align: center;"><span>(подпись)</span></div>
          </td>
          <td style="border:none; text-align: center;">
            {{substr($info->name, 0, 1)}}. {{substr($info->patronymic, 0, 1)}}. {{$info->surname}}
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
</body>
</html>
