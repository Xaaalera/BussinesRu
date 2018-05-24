**Надстройка для класса по работе с Бизнес РУ**

Данная надстройка упрощяет  работу с такой "приятной системой" как Бизнес РУ. Обо всём по порядку.  
На момент написания доккументации надстройка умеет создавать только Сделки.  
Кракая инстукция и описание методов.

1. Вы должны зайти в свой личный кабинет Бизнес ру перейти по url типа https://exemple.class365.ru/externalapplication там вы должены создать ключ для работы с API проекта
, там же вы получите идентификатор интеграции. 
2. Выбрав пункт CRM->Сделка или слева вверху создайте новую сделку, сразу пропишите нужные доп поля,  руками выберие отвественного мененджера.

3. Теперь можно приступить к ознакомлению с методами класса.

***ВНИМАНИЕ API РАБОТАЕТ ТОЛЬКО С КОДИРОВКОЙ UTF-8***  
Сначала подключаем файл , сущности с которыми будем работать и  устанавливаем онсовные настройки.
```
require_once($_SERVER["DOCUMENT_ROOT"].'/Businessru/autoload.php');
use Business\Status;
use Business\Business;
use Business\Order;
use Business\Contact;
Business::$productId = 1232;
Business::$apiKey = 'api-key';
Business::$urlProject = 'https://exemple.class365.ru';
```
* 1ый параметр - это идентификатор интеграции который вы получили в пункте 1.
* 2ой параметр - API ключь  который вы получили в пункте 1. 
* 3ий параметр - url вашего домена  он должен быть как в примере (на конце не 
должно быть слешка, содержать хттпс)
 
После создания экземпляра класса вы можете приступить к созданию Контрагента(клиента) 

```
$contact = new Contact();
$contact->setName('name'));
$contact->setPhone(74951111111);
$contact->createPartner();
```

Думаю из примера всё понятно.

Список ID  соотношение ид с видом связи.
```
[id] =>   1[name]  => Телефон,         [id] =>  2[name]  => Skype,
[id] =>   3[name]  => Адрес,           [id] =>  4[name]  => Email,
[id] =>   5[name]  => Прочее,          [id] =>  6[name]  => Сайт,
[id] =>   7[name]  => ICQ,             [id] =>  8[name]  => Вконтакте,
[id] =>   9[name]  => Facebook,        [id] =>  10[name] => Twitter,
[id] =>  11[name] => Одноклассники
```
Этот же список есть в самом классе Business , достаточно его открыть он в самом начале(сделано для вашего удобства)

Функция `createPartner()` проверяет так же на  то ,есть ли данный контакт в системе, если он уже есть,  
то  не создает новый контакт.

После создания Партнера  создаём сделку.
```
order = new Order();
$order->setAuthorEmployeeId(282811);
$order->setResponsibleEmployeeId(282811);
$order->setOrganizationId(75535);
$order->setExecutorEmployeeId(87410);
$order->setName("Заявка с формы Обратная связь сайта один");
$order->setComment('descriptions');

```
Обо всех параметрах
* 1ый параметр автор  Сделки , его нужно смотреть вручную (подробнее ниже)
* 2ой параметр отвественный автор( я не до конца понял что это, подробнее где взять ниже)
* 3ий параметр организатор (подробнее где посмотреть ниже)
* 4ый параметр отвественный мененджер за сделку (где смотреть ниже)
* 5ый параметр название сделки
* 6ой параметр Описание сделки

Для того чтобы установить другие поля используйте следующую конструкцию
```
$order->setCustomFields($name,$value);
```

Чтобы отправить сделку достаточно использовать конструкцию
```
$order->createOrder();
```
Для сущности Сделка - работает то же самое (Deal) ;

Обязательными параметрами являются с 1 по 5 без них сделка не создастся

Теперь о том как получить  дополнительные поля и ID которые нужны
Нужно выполнить следующую функцию и вывести её результат.
```
$order->getOrders(['name' => 'Название вашей созданной сделки вручную','with_additional_fields'=>1]);
```
Вывев результат вы найдете всё вас интересующее

****Прочие методы и классы которые будут полезны****

```
$contact->newPartnerContactInfo($array,$partnerid);
```
Функия если вам нужно создать контакту новые контактные данные,  
 но не создаватьсамого партнера, 
 * 1ый параметр массив с данными(id указаны выше)
 * 2ой параметр партнер ID - необязательный параметр если ранее была вызвана функция setPartnerId() ; 
 
```
$contact->setPartnerId(id)
```
Установить новый партнер ID - 
* 1 параметр ID.


```
$contact->getPartnerId()
```

Получить партнер ID

```

$Deals->getDeals($options)
```
Получить все сделки или определенную (если передать в  опшонс параметры)
* 1 параметр  - массив с опциями.

```

$contact->getPartners($options)
```
Получить всех партнеров или определенного (если передать в  опшонс параметры)
* 1 параметр  - массив с опциями.

```

$contact->getContactInfo($options)
```
Получить информацию о контакте с данными 
* 1 параметр  - массив с опциями обязательно

Боевые примеры можно посмотреть в файле рядом  например по колтрекингу.
Выгрузка и создание лида по звонку сделаны для сущности - customerorders - Заказы Покупателей.


//TODO доработать страницу выгрузки, дописать недостающие методы класса,  дописать  exceptions  и кучу всего...
