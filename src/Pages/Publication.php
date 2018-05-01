<?php

/**
 * This file is part of SilverWare.
 *
 * PHP version >=5.6.0
 *
 * For full copyright and license information, please view the
 * LICENSE.md file that was distributed with this source code.
 *
 * @package SilverWare\Publications\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-publications
 */

namespace SilverWare\Publications\Pages;

use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\GridField\GridField;
use SilverWare\Extensions\Model\DetailFieldsExtension;
use SilverWare\Forms\GridField\GridFieldConfig_OrderableEditor;
use SilverWare\Publications\Model\PublicationFile;
use Page;

/**
 * An extension of the page class for a publication.
 *
 * @package SilverWare\Publications\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-publications
 */
class Publication extends Page
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Publication';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Publications';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'An individual publication within a publication category';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware/publications: admin/client/dist/images/icons/Publication.png';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_Publication';
    
    /**
     * Determines whether this object can exist at the root level.
     *
     * @var boolean
     * @config
     */
    private static $can_be_root = false;
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = 'none';
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'Date' => 'Date',
        'Pages' => 'AbsoluteInt',
        'Author' => 'Varchar(128)'
    ];
    
    /**
     * Defines the has-many associations for this object.
     *
     * @var array
     * @config
     */
    private static $has_many = [
        'Files' => PublicationFile::class
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'ShowInMenus' => 0
    ];
    
    /**
     * Defines the extension classes to apply to this object.
     *
     * @var array
     * @config
     */
    private static $extensions = [
        DetailFieldsExtension::class
    ];
    
    /**
     * Defines the asset folder for uploaded meta images.
     *
     * @var string
     * @config
     */
    private static $meta_image_folder = 'Publications';
    
    /**
     * Defines the list item details to show for this object.
     *
     * @var array
     * @config
     */
    private static $list_item_details = [
        'author' => [
            'icon' => 'user',
            'text' => '$Author'
        ],
        'pages' => [
            'icon' => 'file-text-o',
            'text' => '$NumberOfPages'
        ]
    ];
    
    /**
     * Defines the detail fields to show for the object.
     *
     * @var array
     * @config
     */
    private static $detail_fields = [
        'date' => [
            'name' => 'Date',
            'icon' => 'calendar',
            'text' => '$MetaDateFormatted'
        ],
        'author' => [
            'name' => 'Author',
            'icon' => 'user',
            'text' => '$Author'
        ],
        'pages' => [
            'name' => 'Pages',
            'icon' => 'file-text-o',
            'text' => '$NumberOfPages'
        ]
    ];
    
    /**
     * Defines the setting for showing the detail fields inline.
     *
     * @var boolean
     * @config
     */
    private static $detail_fields_inline = true;
    
    /**
     * Defines the setting for hiding the detail fields header.
     *
     * @var boolean
     * @config
     */
    private static $detail_fields_hide_header = true;
    
    /**
     * Defines the setting for hiding the detail field names.
     *
     * @var boolean
     * @config
     */
    private static $detail_fields_hide_names = true;
    
    /**
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Modify Field Objects:
        
        $fields->dataFieldByName('Content')->setTitle($this->fieldLabel('Content'));
        
        // Create Main Fields:
        
        $fields->addFieldsToTab(
            'Root.Main',
            [
                DateField::create(
                    'Date',
                    $this->fieldLabel('Date')
                )
            ],
            'Content'
        );
        
        // Create Details Tab:
        
        $fields->findOrMakeTab(
            'Root.Details',
            $this->fieldLabel('Details')
        );
        
        // Create Details Fields:
        
        $fields->addFieldsToTab(
            'Root.Details',
            [
                TextField::create(
                    'Author',
                    $this->fieldLabel('Author')
                ),
                TextField::create(
                    'Pages',
                    $this->fieldLabel('Pages')
                )
            ]
        );
        
        // Create Files Tab:
        
        $fields->findOrMakeTab(
            'Root.Files',
            $this->fieldLabel('Files')
        );
        
        // Create Files Field:
        
        $fields->addFieldsToTab(
            'Root.Files',
            [
                GridField::create(
                    'Files',
                    $this->fieldLabel('Files'),
                    $this->Files(),
                    GridFieldConfig_OrderableEditor::create()
                )
            ]
        );
        
        // Answer Field Objects:
        
        return $fields;
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
        
        $labels['Date']  = _t(__CLASS__ . '.DATE', 'Date');
        $labels['Files'] = _t(__CLASS__ . '.FILES', 'Files');
        $labels['Pages'] = _t(__CLASS__ . '.PAGES', 'Pages');
        $labels['Author'] = _t(__CLASS__ . '.AUTHOR', 'Author');
        $labels['Content'] = _t(__CLASS__ . '.OVERVIEW', 'Overview');
        
        // Define Relation Labels:
        
        if ($includerelations) {
            
        }
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Populates the default values for the fields of the receiver.
     *
     * @return void
     */
    public function populateDefaults()
    {
        // Populate Defaults (from parent):
        
        parent::populateDefaults();
        
        // Populate Defaults:
        
        $this->Date = date('Y-m-d');
    }
    
    /**
     * Answers the parent category of the receiver.
     *
     * @return PublicationCategory
     */
    public function getCategory()
    {
        return $this->getParent();
    }
    
    /**
     * Answers the meta date for the receiver.
     *
     * @return DBDate
     */
    public function getMetaDate()
    {
        return $this->dbObject('Date');
    }
    
    /**
     * Answers a list of the enabled files within the receiver.
     *
     * @return DataList
     */
    public function getEnabledFiles()
    {
        return $this->Files()->filter('Disabled', 0);
    }
    
    /**
     * Answers the number of pages within the publications.
     *
     * @return string
     */
    public function getNumberOfPages()
    {
        if ($this->Pages > 0) {
            
            if ($this->Pages == 1) {
                $noun = _t(__CLASS__ . '.PAGE', 'page');
            } else {
                $noun = _t(__CLASS__ . '.PAGES', 'pages');
            }
            
            return sprintf('%d %s', $this->Pages, $noun);
            
        }
    }
    
    /**
     * Answers true if the file information is to be shown in the template.
     *
     * @return boolean
     */
    public function getShowFileInfo()
    {
        return $this->getCategory()->ShowFileInfo;
    }
    
    /**
     * Answers the heading text for the files section.
     *
     * @return string
     */
    public function getFilesHeadingText()
    {
        return _t(__CLASS__ . '.FILES', 'Files');
    }
}
