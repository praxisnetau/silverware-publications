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

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\ArrayList;
use SilverStripe\ORM\SS_List;
use SilverStripe\View\ArrayData;
use SilverWare\Extensions\Lists\ListViewExtension;
use SilverWare\Extensions\Model\ImageDefaultsExtension;
use SilverWare\Forms\FieldSection;
use SilverWare\Lists\ListSource;
use Page;

/**
 * An extension of the page class for a publication archive.
 *
 * @package SilverWare\Publications\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-publications
 */
class PublicationArchive extends Page implements ListSource
{
    /**
     * Define constants.
     */
    const SORT_ORDER     = 'order';
    const SORT_TITLE     = 'title';
    const SORT_DATE_ASC  = 'date-asc';
    const SORT_DATE_DESC = 'date-desc';
    
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Publication Archive';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Publication Archives';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'Holds a series of publications organised into categories';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware/publications: admin/client/dist/images/icons/PublicationArchive.png';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_PublicationArchive';
    
    /**
     * Defines the default child class for this object.
     *
     * @var string
     * @config
     */
    private static $default_child = PublicationCategory::class;
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'SortOrder' => 'Varchar(16)',
        'ShowFileInfo' => 'Boolean'
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'ShowFileInfo' => 1,
        'ImageDefaultAlignment' => 'right',
        'ImageDefaultResizeWidth' => 300,
        'ImageDefaultResizeHeight' => 400,
        'ImageDefaultResizeMethod' => 'scale-width'
    ];
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = [
        PublicationCategory::class
    ];
    
    /**
     * Defines the extension classes to apply to this object.
     *
     * @var array
     * @config
     */
    private static $extensions = [
        ListViewExtension::class,
        ImageDefaultsExtension::class
    ];
    
    /**
     * Defines the default values for the list view component.
     *
     * @var array
     * @config
     */
    private static $list_view_defaults = [
        'ImageAlign' => 'right',
        'ImageResizeWidth' => 150,
        'ImageResizeHeight' => 200,
        'ImageResizeMethod' => 'scale-width',
        'HideNoDataMessage' => 1
    ];
    
    /**
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Define Placeholder:
        
        $placeholder = _t(__CLASS__ . '.DROPDOWNDEFAULT', '(default)');
        
        // Create Options Fields:
        
        $fields->addFieldsToTab(
            'Root.Options',
            [
                FieldSection::create(
                    'PublicationOptions',
                    $this->fieldLabel('PublicationOptions'),
                    [
                        DropdownField::create(
                            'SortOrder',
                            $this->fieldLabel('SortOrder'),
                            $this->getSortOrderOptions()
                        )->setEmptyString(' ')->setAttribute('data-placeholder', $placeholder),
                        CheckboxField::create(
                            'ShowFileInfo',
                            $this->fieldLabel('ShowFileInfo')
                        )
                    ]
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
        
        $labels['SortOrder'] = _t(__CLASS__ . '.SORTORDER', 'Sort order');
        $labels['ShowFileInfo'] = _t(__CLASS__ . '.SHOWFILEINFORMATION', 'Show file information');
        $labels['PublicationOptions'] = _t(__CLASS__ . '.PUBLICATIONS', 'Publications');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers a list of publications within the publication archive.
     *
     * @return DataList
     */
    public function getPublications()
    {
        return $this->sort(Publication::get()->filter('ParentID', $this->AllChildren()->column('ID') ?: null));
    }
    
    /**
     * Answers a list of publications within the receiver.
     *
     * @return DataList
     */
    public function getListItems()
    {
        return $this->getPublications();
    }
    
    /**
     * Answers all categories within the receiver.
     *
     * @return DataList
     */
    public function getAllCategories()
    {
        return PublicationCategory::get()->filter('ParentID', $this->ID);
    }
    
    /**
     * Answers all non-empty categories within the receiver.
     *
     * @return ArrayList
     */
    public function getNonEmptyCategories()
    {
        return $this->getAllCategories()->filterByCallback(function ($category) {
            return $category->hasPublications();
        });
    }
    
    /**
     * Answers all visible categories within the receiver.
     *
     * @return ArrayList
     */
    public function getVisibleCategories()
    {
        $data = ArrayList::create();
        
        foreach ($this->getNonEmptyCategories()->filter('ShowOnSeparatePage', 0) as $category) {
            
            $data->push(
                ArrayData::create([
                    'Title' => $category->Title,
                    'Category' => $category,
                    'Publications' => $this->getPublicationList($category)
                ])
            );
            
        }
        
        return $data;
    }
    
    /**
     * Answers the publication list component for the template.
     *
     * @param PublicationCategory $category
     *
     * @return BaseListComponent
     */
    public function getPublicationList(PublicationCategory $category)
    {
        $list = clone $this->getListComponent();
        
        $list->setSource($category->getPublications());
        $list->setStyleIDFrom($this, $category->Title);
        
        return $list;
    }
    
    /**
     * Answers a message string to be shown when no data is available.
     *
     * @return string
     */
    public function getNoDataMessage()
    {
        return _t(__CLASS__ . '.NODATAAVAILABLE', 'No data available.');
    }
    
    /**
     * Answers an array of options for the sort order field.
     *
     * @return array
     */
    public function getSortOrderOptions()
    {
        return [
            self::SORT_ORDER => _t(__CLASS__ . '.ORDER', 'Order'),
            self::SORT_TITLE => _t(__CLASS__ . '.TITLE', 'Title'),
            self::SORT_DATE_ASC => _t(__CLASS__ . '.DATEASCENDING', 'Date Ascending'),
            self::SORT_DATE_DESC => _t(__CLASS__ . '.DATEDESCENDING', 'Date Descending')
        ];
    }
    
    /**
     * Sorts the given list of publications.
     *
     * @param SS_List $list
     *
     * @return SS_List
     */
    public function sort(SS_List $list)
    {
        switch ($this->SortOrder) {
            case self::SORT_DATE_ASC:
                return $list->sort('Date', 'ASC');
            case self::SORT_DATE_DESC:
                return $list->sort('Date', 'DESC');
            case self::SORT_ORDER:
                return $list->sort('Sort');
            case self::SORT_TITLE:
                return $list->sort('Title', 'ASC');
        }
        
        return $list;
    }
}
