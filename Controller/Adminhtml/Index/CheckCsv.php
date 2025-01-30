<?php

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class CheckCsv
 * PinBlooms\MasterData\Controller\Adminhtml\Index
 */
class CheckCsv extends \Magento\Backend\App\Action
{
    /**
     * @var array
     */
    public $allowedExtensions;
    /**
     * @var string
     */
    public $basePath;
    /**
     * @var string
     */
    public $baseTmpPath;
    /**
     * @var \PinBlooms\MasterData\Logger\Logger
     */
    public $logger;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    public $storeManager;
    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    public $uploaderFactory;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    public $mediaDirectory;
    /**
     * @var \Magento\MediaStorage\Helper\File\Storage\Database
     */
    public $coreFileStorageDatabase;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    public $varDirectory;
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    public $directoryList;
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    public $fileIo;
    /**
     * @var \Magento\Framework\File\Csv
     */
    public $csv;
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    public $file;

    /**
     * CheckCsv constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Io\File $fileIo
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param \Magento\Framework\File\Csv $csv
     * @param \PinBlooms\MasterData\Logger\Logger $logger
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $uploaderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Io\File $fileIo,
        \Magento\Framework\Filesystem\Driver\File $file,
        \Magento\Framework\File\Csv $csv,
        \PinBlooms\MasterData\Logger\Logger $logger
    ) {
        parent::__construct($context);
        $this->mediaDirectory = $filesystem
            ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->varDirectory = $filesystem
            ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
        $this->uploaderFactory = $uploaderFactory;
        $this->storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->fileIo = $fileIo;
        $this->logger = $logger;
        $this->file = $file;
        $this->csv = $csv;
        $this->baseTmpPath = \PinBlooms\MasterData\Model\Options::BASE_TMP_PATH;
        $this->basePath = \PinBlooms\MasterData\Model\Options::BASE_PATH;
        $this->allowedExtensions = ['csv'];
    }

    /**
     * Execute
     *
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $this->logger->info(
                "PinBlooms_MasterData",
                ['Message' => '', 'Path' => __METHOD__]
            );
            $result = $this->saveFileToTmpDir('custom_csv');
            if (isset($result['path']) && isset($result['file'])) {
                $csvFile = $result['path'] . '/' . $result['file'];
                $data = $this->csv->getData($csvFile);
                if (!empty($data) && isset($data[0])) {
                    $headers = $data[0];
                    $csvActualHeader = [
                        \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_SKU,
                        \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_PRODUCT_TYPE,
                        \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_CATEGORY,
                        \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_ISSUE_TYPE,
                        \PinBlooms\MasterData\Model\MasterData::PINBLOOMS_MASTERDATA_M_NAME,
                    ];
                    // only first header column data
                    $csvActualHeaderResult = array_diff($csvActualHeader, $headers);
                    if (empty($csvActualHeaderResult)) {
                        $result['cookie'] = [
                            'name' => $this->_getSession()->getName(),
                            'value' => $this->_getSession()->getSessionId(),
                            'lifetime' => $this->_getSession()->getCookieLifetime(),
                            'path' => $this->_getSession()->getCookiePath(),
                            'domain' => $this->_getSession()->getCookieDomain(),
                        ];
                    } else {
                        $result = [
                            'error' => 'Uploaded CSV Header Not Match With Configured Headers : ' .
                                json_encode($csvActualHeaderResult, JSON_PRETTY_PRINT),
                            'errorcode' => '3'
                        ];
                    }
                } else {
                    $result = ['error' => 'No Data Exist In Csv', 'errorcode' => '2'];
                }
            } else {
                $result = ['error' => 'Csv File Does Not Exist', 'errorcode' => '1'];
            }
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }

    /**
     * SaveFileToTmpDir
     *
     * @param string $fileId
     *
     * @return array
     *
     * @throws \Magento\Framework\Exception\LocalizedException, \Magento\Framework\Exception\NoSuchEntityException
     */
    public function saveFileToTmpDir($fileId = 'custom_csv')
    {
        $baseTmpPath = $this->getBaseTmpPath();
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setAllowedExtensions($this->getAllowedExtensions());
        $result = $uploader->save($this->varDirectory->getAbsolutePath($baseTmpPath));
        if (!$result) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('File can not be saved to the destination folder.')
            );
        }
        $result['tmp_name'] = str_replace('\\', '/', $result['tmp_name']);
        $result['path'] = str_replace('\\', '/', $result['path']);
        $result['url'] = $this->storeManager
            ->getStore()
            ->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            ) . $this->getFilePath($baseTmpPath, $result['file']);
        $result['name'] = $result['file'];
        return $result;
    }

    /**
     * SetBaseTmpPath
     *
     * @param string $baseTmpPath
     */
    public function setBaseTmpPath($baseTmpPath)
    {
        $this->baseTmpPath = $baseTmpPath;
    }

    /**
     * SetBasePath
     *
     * @param string $basePath
     */
    public function setBasePath($basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * SetAllowedExtensions
     *
     * @param array $allowedExtensions
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * GetBaseTmpPath
     *
     * @return string
     */
    public function getBaseTmpPath()
    {
        return $this->baseTmpPath;
    }

    /**
     * GetBasePath
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->basePath;
    }

    /**
     * GetAllowedExtensions
     *
     * @return array
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }

    /**
     * GetFilePath
     *
     * @param string $path
     * @param string $imageName
     *
     * @return string
     */
    public function getFilePath($path, $imageName)
    {
        return rtrim($path, '/') . '/' . ltrim($imageName, '/');
    }
}
