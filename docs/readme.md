-- FIX AFTER DEPLOY

---
if ($result && property_exists($result, 'channels')) { // @todo fix
$result->channels = get_object_vars($result->channels);
}

Pusher.php in_line 538
---

---
$logger = clone app(self::class);
$logger = app(self::class);

WebsocketLogger.php in_line 18
--
if (! empty($config['serializer'])) {
$serializer = 'Redis::SERIALIZER_' . strtoupper($config['serializer']);
if( defined($serializer) ) {
$serializer_const = constant($serializer);
$client->setOption(Redis::OPT_SERIALIZER, $serializer_const);
}
}
