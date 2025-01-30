<?php

namespace PinBlooms\MasterData\Ui\DataProvider\MasterData;

use Magento\Framework\App\Request\DataPersistorInterface;
use PinBlooms\MasterData\Model\ResourceModel\MasterData\CollectionFactory;

class Form extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * loadedData variable
     *
     * @var [type]
     */
    protected $loadedData;

    /**
     * CollectionFactory variable
     *
     * @var CollectionFactory
     */
    protected $collection;

    /**
     * DataPersistorInterface variable
     *
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    private $request;

    /**
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    /**
     * Prepares Meta
     *
     * @param array $meta
     * @return array
     */
    public function prepareMeta(array $meta)
    {
        return $meta;
    }

    /**
     * GetData function
     *
     * @return void
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        foreach ($this->collection->getItems() as $item) {
            $post = $item->load($item->getId()); //temporary fix
            $data = $item->getData();
            $this->loadedData[$item->getEntityId()] = $data;
        }
        $data = $this->dataPersistor->get('masterdata_form_data');

        if (!empty($data)) {
            $post = $this->collection->getNewEmptyItem();
            $post->setData($data);
            $this->loadedData[$post->getEntityId()] = $post->getData();
            $this->dataPersistor->clear('masterdata_form_data');
        }

        return $this->loadedData;
    }
}
