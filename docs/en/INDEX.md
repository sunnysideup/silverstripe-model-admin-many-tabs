# tl;dr

```php

// namespace statement here

// use statements here

class MyModelAdmin extends ModelAdmin
{

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);

        if (MySpecialClass::class === $this->modelClass) {
            TabsBuilder::add_many_tabs(
                $arrayOfTabs = [
                    [
                        'TabName' => 'LowCostSales',
                        'Title' => 'Low Cost Sales',
                        'List' => Order::get()->filter(['Total:LowerThan' => 400]),
                    ],
                    [
                        'TabName' => 'HighCostSales',
                        'Title' => 'High Cost Sales',
                        'List' => Order::get()->filter(['Total:GreaterThan' => 400]),
                    ]
                ],
                $form,
                $this->modelClass,
                $itemsPerPage = 10
            );
        }
        return $form;
    }
}
```
