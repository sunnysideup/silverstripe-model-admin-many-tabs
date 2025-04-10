<?php

namespace Sunnysideup\ModelAdminManyTabs\Api;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldAddNewButton;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldDetailForm;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\Lumberjack\Forms\GridFieldConfig_Lumberjack;

class TabsBuilder
{
    /**
     * @param array $arrayOfTabs  contains: [
     *                            TabName => string,
     *                            Title => string,
     *                            List => DataList
     *                            AllowAddNew => bool (model must have CMSEditLink)
     *                            ]
     * @param int   $itemsPerPage - optional
     */
    public static function add_many_tabs(array $arrayOfTabs, Form $form, string $modelToReplace, $itemsPerPage = 100)
    {
        $modelToReplaceName = str_replace('\\', '-', $modelToReplace);
        $fields = $form->Fields();
        $fields->insertAfter(
            $modelToReplaceName,
            $parentTab = new TabSet(
                $modelToReplaceName . 'inner',
                []
            )
        );
        $fields->removeByName($modelToReplaceName);
        $singleton = Injector::inst()->get($modelToReplace);
        //important!
        foreach ($arrayOfTabs as $item) {
            if ($singleton instanceof SiteTree) {
                $config = GridFieldConfig_Lumberjack::create($itemsPerPage);
            } else {
                $config = GridFieldConfig_RecordEditor::create($itemsPerPage);
            }
            if (!(isset($item['AllowAddNew']) && $item['AllowAddNew'])) {
                $config->removeComponentsByType(GridFieldAddNewButton::class);
            }
            $gridField = new GridField(
                $item['TabName'],
                $item['Title'],
                $item['List'],
                $config
            );
            $count = $item['List']->count();
            $gridField->setForm($form);
            $parentTab->push(
                new Tab(
                    str_replace(' ', '', (string) $item['Title']),
                    $item['Title'] . ' (' . $count . ')',
                    $gridField
                )
            );
            /**  @var GridFieldDetailForm $editForm */
            $editForm = $gridField->getConfig()->getComponentByType(GridFieldDetailForm::class);
            if ($editForm) {
                $editForm->setRedirectMissingRecords(true);
            }
        }
    }
}
