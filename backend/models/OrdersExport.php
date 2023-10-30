<?php

namespace backend\models;

use common\models\Order;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class OrdersExport extends Model
{
    public $provider;


    public function send()
    {
        /** @var ActiveDataProvider $provider */
        $provider = $this->provider;
        $provider->pagination = false;


        $rows = [];
        $rows[] = $this->prepareHeaders();

        $provider->prepare(true);
        $models = array_values($provider->getModels());

        foreach ($models as $model){
            /** @var  $model Order **/
            $rows[] = [
                $model->id,
                $model->good->name ?? '',
                $model->prettyPrice,
                $model->client_name,
                $model->client_phone
            ];
        }


        ob_start();
        $fh = @fopen('php://output', 'w');

        foreach ($rows as &$row) {
            fputcsv($fh, $row);
        }
        $output = ob_get_clean();
        fclose($fh);

        return $output;
    }

    protected function prepareHeaders()
    {
        return [
            'Id',
            'Good',
            'Price',
            'Name',
            'Phone'
        ];
    }

}