# tl;dr

```php

// namespace statement here

// use statements here

class MyModelAdmin extends ModelAdmin
{

    public function getEditForm($id = null, $fields = null)
    {
        $form = parent::getEditForm($id, $fields);
        $fields = $form->Fields();

        if (MySpecialClass::class === $this->modelClass) {
            TabsBuilder::add_many_tabs($arrayOfTabs, $id, $fields, $form)
        }
        return $form;
    }
}
```
