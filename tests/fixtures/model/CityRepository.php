<?php
/***********************************************************************
 *
 * Ting - PHP Datamapper
 * ==========================================
 *
 * Copyright (C) 2014 CCM Benchmark Group. (http://www.ccmbenchmark.com)
 *
 ***********************************************************************
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you
 * may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 **********************************************************************/

namespace tests\fixtures\model;

use CCMBenchmark\Ting\Repository\Metadata;
use CCMBenchmark\Ting\Serializer\SerializerFactoryInterface;

class CityRepository extends \CCMBenchmark\Ting\Repository\Repository
{
    public static $options;

    public static function initMetadata(SerializerFactoryInterface $serializerFactory, array $options = [])
    {
        self::$options = $options;

        $metadata = new Metadata($serializerFactory);

        $metadata->setEntity('tests\fixtures\model\City');
        $metadata->setConnectionName('main');
        $metadata->setDatabase('bouh_world');
        $metadata->setTable('T_CITY_CIT');

        $metadata->addField(array(
            'primary'       => true,
            'autoincrement' => true,
            'fieldName'     => 'id',
            'columnName'    => 'cit_id',
            'type'          => 'int'
        ));

        $metadata->addField(array(
            'fieldName'  => 'name',
            'columnName' => 'cit_name',
            'type'      => 'string'
        ));

        $metadata->addField(array(
            'fieldName'  => 'zipcode',
            'columnName' => 'cit_zipcode',
            'type'       => 'string'
        ));

        return $metadata;
    }
}
