<?php
namespace App\Console\Commands;

use App\Models\ObjectMonitoring;
use Illuminate\Console\Command;
use \PhpMqtt\Client\ConnectionSettings;
use \PhpMqtt\Client\MqttClient;

class MQTTModusystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:m-q-t-t-modusystem';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $server             = 'mqtt.lift-online.eu';
        $port               = 8883;
        $clientId           = rand(5, 15);
        $username           = 'digilevel';
        $password           = 'ohpei5Ge';
        $clean_session      = false;
        $mqtt_version       = MqttClient::MQTT_3_1;
        $connectionSettings = (new ConnectionSettings)
            ->setUsername($username)
            ->setPassword($password)
            ->setKeepAliveInterval(60)
            ->setTlsCertificateAuthorityFile("mqtt-ca.crt")
            ->setConnectTimeout(3)
            ->setUseTls(true);
        $mqtt = new MqttClient($server, $port, $clientId, $mqtt_version);
        $mqtt->connect($connectionSettings, $clean_session);
        $mqtt->subscribe('00000688/#', function ($topic, $message) {

            $data_message = explode(" ", $message);
            $value        = $data_message[0] ?? 0;

            //Find the date
            //  $dateTime = preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}.\d{3}Z/', $string, $matches);
            //   $dateTime = new DateTime($matches[0]);
            // $dateTime = $dateTime->format('Y-m-d H:i:s');

            $data_topic = explode('/', $topic);

            $uuid     = $data_topic[2] ?? 0;
            $category = $data_topic[4] ?? 0;
            $param01  = $data_topic[5] ?? 0;
            $param02  = $data_topic[6] ?? 0;

            $data_insert = ObjectMonitoring::updateOrCreate(
                [
                    "category"           => $category,
                    "external_object_id" => $uuid,
                    "value"              => $value,
                    //   "date_time"          => $$matches[0],
                ],
                [
                    //     "date_time" => $$matches[0],
                    "param01" => $param01,
                    "param02" => $param02,
                    "value"   => $value,
                    "brand"   => "modusystem",
                ]
            );

        }, 0);

        $mqtt->loop(true);

    }
}
