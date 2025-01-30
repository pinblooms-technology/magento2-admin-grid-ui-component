<?php

namespace PinBlooms\MasterData\Controller\Adminhtml\Index;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Filesystem\DirectoryList;

class Sample extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    private $fileFactory;
    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    private $directory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $filesystemIo;
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    private $driverFile;
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * Sample constructor.
     * @param Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\DirectoryList $directoryList
     * @param \Magento\Framework\Filesystem\Driver\File $driverFile
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Filesystem\Io\File $filesystemIo
     */
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\Filesystem\Driver\File $driverFile,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Filesystem\Io\File $filesystemIo
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->driverFile = $driverFile;
        $this->fileFactory = $fileFactory;
        $this->filesystemIo = $filesystemIo;
    }

    /**
     * Execute
     */
    public function execute()
    {
        try {
            $filePath = substr(__DIR__, 0, -26) . 'sample.csv';
            $cpPath = $this->createDir(
                \PinBlooms\MasterData\Model\Options::BASE_TMP_PATH,
                'var'
            );

            if (isset($cpPath['path']) && !empty($cpPath['path'])) {
                $copyFileFullPath = $cpPath['path'] . '/sample.csv';
                $this->filesystemIo->cp($filePath, $copyFileFullPath);
                $content['type'] = 'filename'; // type has to be "filename"
                $content['value'] = $copyFileFullPath; // path where file place
                // if you add 1 then it will be delete from server after being download, otherwise add 0.
                $content['rm'] = 1;
                return $this->fileFactory->create('sample.csv', $content, DirectoryList::VAR_DIR);
            } else {
                $this->messageManager->addErrorMessage(
                    __('Error : ' . (isset($cpPath['action']) ? $cpPath['action'] : 'No File Found.'))
                );
                $resultRedirect = $this->resultRedirectFactory->create();
                $resultRedirect->setPath('*/*/import');
                return $resultRedirect;
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Exception : ' . $e->getMessage()));
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('*/*/import');
            return $resultRedirect;
        }
    }

    /**
     * CreateDir
     *
     * @param string $name
     * @param string $code
     *
     * @return array
     *
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    private function createDir(
        $name = \PinBlooms\MasterData\Model\Options::BASE_TMP_PATH,
        $code = 'var'
    ) {
        $path = $this->directoryList->getPath($code) . '/' . $name;
        try {
            if ($this->driverFile->isExists($path)) {
                return ['status' => true, 'path' => $path, 'action' => 'dir_exists'];
            } else {
                $this->filesystemIo->mkdir($path, 0775, true);
                return  ['status' => true, 'path' => $path, 'action' => 'dir_created'];
            }
        } catch (\Exception $e) {
            return ['status' => false, 'action' => $e->getMessage()];
        }
    }
}
