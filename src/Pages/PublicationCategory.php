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
use SilverWare\Extensions\Lists\ListViewExtension;
use SilverWare\Extensions\Model\ImageDefaultsExtension;
use SilverWare\Forms\FieldSection;
use SilverWare\Lists\ListSource;
use Page;

/**
 * An extension of the page class for a publication category.
 *
 * @package SilverWare\Publications\Pages
 * @author Colin Tucker <colin@praxis.net.au>
 * @copyright 2018 Praxis Interactive
 * @license https://opensource.org/licenses/BSD-3-Clause BSD-3-Clause
 * @link https://github.com/praxisnetau/silverware-publications
 */
class PublicationCategory extends Page implements ListSource
{
    /**
     * Human-readable singular name.
     *
     * @var string
     * @config
     */
    private static $singular_name = 'Publication Category';
    
    /**
     * Human-readable plural name.
     *
     * @var string
     * @config
     */
    private static $plural_name = 'Publication Categories';
    
    /**
     * Description of this object.
     *
     * @var string
     * @config
     */
    private static $description = 'A category within a publication archive which holds a series of publications';
    
    /**
     * Icon file for this object.
     *
     * @var string
     * @config
     */
    private static $icon = 'silverware/publications: admin/client/dist/images/icons/PublicationCategory.png';
    
    /**
     * Defines the table name to use for this object.
     *
     * @var string
     * @config
     */
    private static $table_name = 'SilverWare_PublicationCategory';
    
    /**
     * Defines the default child class for this object.
     *
     * @var string
     * @config
     */
    private static $default_child = Publication::class;
    
    /**
     * Determines whether this object can exist at the root level.
     *
     * @var boolean
     * @config
     */
    private static $can_be_root = false;
    
    /**
     * Maps field names to field types for this object.
     *
     * @var array
     * @config
     */
    private static $db = [
        'ShowOnSeparatePage' => 'Boolean',
        'ShowContentInArchive' => 'Boolean'
    ];
    
    /**
     * Defines the default values for the fields of this object.
     *
     * @var array
     * @config
     */
    private static $defaults = [
        'ListInherit' => 1,
        'HideFromMainMenu' => 1,
        'ShowOnSeparatePage' => 0,
        'ShowContentInArchive' => 0
    ];
    
    /**
     * Defines the allowed children for this object.
     *
     * @var array|string
     * @config
     */
    private static $allowed_children = [
        Publication::class
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
     * Answers a list of field objects for the CMS interface.
     *
     * @return FieldList
     */
    public function getCMSFields()
    {
        // Obtain Field Objects (from parent):
        
        $fields = parent::getCMSFields();
        
        // Create Options Tab:
        
        $fields->findOrMakeTab('Root.Options', $this->fieldLabel('Options'));
        
        // Create Options Fields:
        
        $fields->addFieldsToTab(
            'Root.Options',
            [
                FieldSection::create(
                    'PublicationCategoryOptions',
                    $this->fieldLabel('PublicationCategory'),
                    [
                        CheckboxField::create(
                            'ShowOnSeparatePage',
                            $this->fieldLabel('ShowOnSeparatePage')
                        ),
                        CheckboxField::create(
                            'ShowContentInArchive',
                            $this->fieldLabel('ShowContentInArchive')
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
        
        $labels['ShowOnSeparatePage'] = _t(__CLASS__ . '.SHOWONSEPARATEPAGE', 'Show on separate page');
        $labels['PublicationCategory'] = _t(__CLASS__ . '.PUBLICATIONCATEGORY', 'Publication Category');
        $labels['ShowContentInArchive'] = _t(__CLASS__ . '.SHOWCONTENTINARCHIVE', 'Show content in archive');
        
        // Answer Field Labels:
        
        return $labels;
    }
    
    /**
     * Answers the parent archive of the receiver.
     *
     * @return PublicationArchive
     */
    public function getArchive()
    {
        return $this->getParent();
    }
    
    /**
     * Answers the publications within the category.
     *
     * @return DataList
     */
    public function getPublications()
    {
        return $this->getArchive()->sort(Publication::get()->filter('ParentID', $this->ID));
    }
    
    /**
     * Answers true if the receiver has at least one publication.
     *
     * @return boolean
     */
    public function hasPublications()
    {
        return $this->getPublications()->exists();
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
     * Answers true if the file information is to be shown in the template.
     *
     * @return boolean
     */
    public function getShowFileInfo()
    {
        return (boolean) $this->getArchive()->ShowFileInfo;
    }
}
