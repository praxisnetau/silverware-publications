<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Publications\Model
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-publications
 */

namespace SilverWare\Publications\Model;

use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\RequiredFields;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverWare\Publications\Pages\Publication;

/**
 * An extension of the data object class for a publication file.
 *
 * @package SilverWare\Publications\Model
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-publications
 */
class PublicationFile extends DataObject
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Publication File';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Publication Files';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_PublicationFile';
    
    /**
     * Defines the default sort field and order for this object.
     *
     * @var string
     * @config
     */
    private static $default_sort = 'Sort';
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'Sort' => 'Int',
        'Name' => 'Varchar(128)',
        'Disabled' => 'Boolean'
    ];
    
    /**
     * Defines the has-one associations for this object.
     *
     * @var array
     * @config
     */
    private static $has_one = [
        'File' => File::class,
        'Publication' => Publication::class
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'Disabled' => 0
    ];
    
    /**
     * Defines the summary fields of this object.
     *
     * @var array
     * @config
     */
    private static $summary_fields = [
        'Name',
        'File.Name',
        'Disabled.Nice'
    ];
    
    /**
     * Defines the asset folder for uploading images.
     *
     * @var string
     * @config
     */
    private static $asset_folder = 'Publications/Files';
    
    /**
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Create Field List and Tab Set:
        
        $fields = FieldList::create(TabSet::create('Root'));
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            [
                TextField::create(
                    'Name',
                    $this->fieldLabel('Name')
                ),
                UploadField::create(
                    'File',
                    $this->fieldLabel('File')
                )->setFolderName($this->getAssetFolder()),
                CheckboxField::create(
                    'Disabled',
                    $this->fieldLabel('Disabled')
                )
            ]
        );
        
        // Extend Field Objects:
        
        $this->extend('updateCMSFields', $fields);
        
        // Answer Field Objects:
        
        return $fields;
    }
    
    /**
     * Answers a validator for the CMS interface.
     *
     * @return RequiredFields
     */
    public function getCMSValidator()
    {
        return RequiredFields::create([
            'Name',
            'File'
        ]);
    }
    
    /**
     * Answers the labels for the fields of the receiver.
     *
     * @param boolean $includerelations Include labels for relations.
     *
     * @return array
     */
    public function fieldLabels($includerelations = true)
    {
        // Obtain Field Labels (from parent):
        
        $labels = parent::fieldLabels($includerelations);
        
        // Define Field Labels:
        
        $labels['FileID'] = _t(__CLASS__ . '.FILE', 'File');
        $labels['PublicationID'] = _t(__CLASS__ . '.PUBLICATION', 'Publication');
        
        $labels['File.Name'] = _t(__CLASS__ . '.FILENAME', 'Filename');
        $labels['Disabled'] = $labels['Disabled.Nice'] = _t(__CLASS__ . '.DISABLED', 'Disabled');
        
        // Define Relation Labels:
        
        if ($includerelations) {
            $labels['File'] = _t(__CLASS__ . '.has_one_File', 'File');
            $labels['Publication'] = _t(__CLASS__ . '.has_one_Publication', 'Publication');
        }
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Event method called before the receiver is written to the database.
     *
     * @return void
     */
    public function onBeforeWrite()
    {
        // Call Parent Event:
        
        parent::onBeforeWrite();
        
        // Publish File:
        
        if ($this->File()->exists()) {
            $this->File()->publishSingle();
        }
    }
    
    /**
     * Answers the asset folder used by the receiver.
     *
     * @return string
     */
    public function getAssetFolder()
    {
        return $this->config()->asset_folder;
    }
    
    /**
     * Answers the URL for downloading the file.
     *
     * @return string
     */
    public function getURL()
    {
        return $this->File()->URL;
    }
    
    /**
     * Answers the type of the file.
     *
     * @return string
     */
    public function getType()
    {
        return strtoupper($this->File()->Extension);
    }
    
    /**
     * Answers the size of the file.
     *
     * @return string
     */
    public function getSize()
    {
        return $this->File()->Size;
    }
    
    /**
     * Answers true if the file information is to be shown in the template.
     *
     * @return boolean
     */
    public function getShowInfo()
    {
        return $this->Publication()->ShowFileInfo;
    }
    
    /**
     * Answers a string of information about the file.
     *
     * @return string
     */
    public function getInfo()
    {
        $info = [];
        
        $info[] = $this->Type;
        
        $info[] = $this->Size;
        
        return implode(' ', $info);
    }
}
