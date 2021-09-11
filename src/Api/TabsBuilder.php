<?php

namespace Sunnysideup\ModelAdminManyTabs\Api;

use SilverStripe\Forms\Form;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\Tab;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig;
use SilverStripe\Forms\GridField\GridFieldPageCount;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\GridField\GridFieldDataColumns;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridFieldToolbarHeader;
use SilverStripe\Forms\GridField\GridFieldSortableHeader;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

use SilverStripe\Core\Injector\Injector;

use SilverStripe\CMS\Model\SiteTree;

use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeState;
use SilverStripe\Lumberjack\Forms\GridFieldConfig_Lumberjack;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeEditButton;
use SilverStripe\Lumberjack\Forms\GridFieldSiteTreeAddNewButton;

use SilverShop\HasOneField\GridFieldHasOneButtonRow;


class TabsBuilder
{

    /**
     *
     * @param array    $arrayOfTabs  contains: [
     *     TabName => string,
     *     Title => string,
     *     List => DataList
     * ]
     * @param Form     $form
     * @param string   $modelToReplace
     * @param int      $itemsPerPage - optional
     */
    public static function add_many_tabs(array $arrayOfTabs, Form $form, string $modelToReplace, $itemsPerPage = 100)
    {
        $modelToReplaceName = str_replace('\\', '-', $modelToReplace);
        $fields->insertAfter(
            $modelToReplaceName,
            $parentTab = new TabSet(
                $modelToReplaceName.'inner',
                []
            )
        );
        $fields->removeByName($modelToReplaceName);
        $singleton = Injector::inst()-get($modelToReplace);
        if($singleton instanceof SiteTree) {
            $config = GridFieldConfig_Lumberjack::create($itemsPerPage);
        } else {
            $config = GridFieldConfig_RecordEditor::create($itemsPerPage);
        }
        foreach ($array as $item) {
            $gridField = new GridField(
                $item['TabName'],
                $item['Title'],
                $item['List'],
                $config
            );
            $gridField->setForm($form);
            $parentTab->push(
                new Tab(
                    str_replace(' ', '', $item['Title']),
                    $item['Title'],
                    $gridField
                )
            );
        }
    }




}
