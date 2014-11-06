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

namespace CCMBenchmark\Ting;

use Pimple\Container;

class Services implements ContainerInterface
{

    protected $container = null;

    public function __construct()
    {
        $this->container = new Container();
        $this->container->offsetSet(
            'ConnectionPool',
            function () {
                return new ConnectionPool();
            }
        );

        $this->container->offsetSet(
            'MetadataRepository',
            function () {
                return new MetadataRepository($this->get('MetadataFactory'));
            }
        );

        $this->container->offsetSet(
            'UnitOfWork',
            function () {
                return new UnitOfWork(
                    $this->get('ConnectionPool'),
                    $this->get('MetadataRepository'),
                    $this->get('QueryFactory')
                );
            }
        );

        $this->container->offsetSet(
            'MetadataFactory',
            function () {
                return new Repository\MetadataFactory($this->get('QueryFactory'));
            }
        );

        $this->container->offsetSet(
            'CollectionFactory',
            $this->container->factory(function () {
                return new Repository\CollectionFactory($this->get('Hydrator'));
            })
        );

        $this->container->offsetSet(
            'QueryFactory',
            function () {
                return new Query\QueryFactory();
            }
        );

        $this->container->offsetSet(
            'Hydrator',
            function () {
                return new Repository\Hydrator($this->get('MetadataRepository'), $this->get('UnitOfWork'));
            }
        );

        $this->container->offsetSet(
            'RepositoryFactory',
            function () {
                return new Repository\RepositoryFactory(
                    $this->get('ConnectionPool'),
                    $this->get('MetadataRepository'),
                    $this->get('QueryFactory'),
                    $this->get('CollectionFactory'),
                    $this->get('UnitOfWork'),
                    $this->get('Cache')
                );
            }
        );

        $this->container->offsetSet(
            'Cache',
            function () {
                $cache = new Cache\Memcached();
                $cache->setConnection(new \Memcached($cache->getPersistentId()));
                return $cache;
            }
        );
    }

    public function set($id, \Closure $callable, $factory = false)
    {
        if ($factory === true) {
            $callable = $this->container->factory($callable);
        }

        $this->container->offsetSet($id, $callable);
        return $this;
    }

    public function get($id)
    {
        return $this->container->offsetGet($id);
    }

    public function has($id)
    {
        return $this->container->offsetExists($id);
    }
}
