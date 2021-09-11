<?php

use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;


namespace Sunnysideup\ModelAdminManyTabs\Api;


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
            $config = self::grid_field_config_for_site_tree($itemsPerPage);
        } else {
            $config = GridFieldConfig_RecordEditor::create($itemsPerPage);
        }
        foreach ($array as $item) {
            $gridField = new GridField(
                $item['TabName'],
                $item['Title'],
                $item['List'],
                GridFieldConfig_Lumberjack::create(100)
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


    protected function grid_field_config_for_site_tree($itemsPerPage = 100)
    {

        $gridFieldConfig = GridFieldConfig::create($itemsPerPage);

        $gridFieldConfig->addComponent(new GridFieldButtonRow('before'));
        $gridFieldConfig->addComponent(new GridFieldSiteTreeAddNewButton('buttons-before-left'));
        $gridFieldConfig->addComponent(new GridFieldToolbarHeader());
        $gridFieldConfig->addComponent(new GridFieldSortableHeader());
        $gridFieldConfig->addComponent(new GridFieldFilterHeader());
        $gridFieldConfig->addComponent(new GridFieldDataColumns());
        $gridFieldConfig->addComponent(new GridFieldSiteTreeEditButton());
        $gridFieldConfig->addComponent(new GridFieldPageCount('toolbar-header-right'));
        $gridFieldConfig->addComponent($pagination = new GridFieldPaginator($itemsPerPage));
        $gridFieldConfig->addComponent(new GridFieldSiteTreeState());

        $pagination->setThrowExceptionOnBadDataType(true);
    }

}
