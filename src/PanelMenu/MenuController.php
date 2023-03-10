<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagmaCore\PanelMenu;

use MagmaCore\Base\Access;
use MagmaCore\PanelMenu\MenuForm;
use MagmaCore\PanelMenu\MenuModel;
use MagmaCore\PanelMenu\MenuColumn;
use MagmaCore\Panelmenu\MenuEntity;
use MagmaCore\PanelMenu\MenuSchema;
use MagmaCore\PanelMenu\MenuCommander;
use MagmaCore\DataObjectLayer\DataLayerTrait;
use MagmaCore\PanelMenu\Event\MenuActionEvent;
use MagmaCore\PanelMenu\MenuItems\MenuItemModel;
use MagmaCore\Base\Exception\BaseInvalidArgumentException;
use MagmaCore\PanelMenu\EventSubscriber\MenuActionSubscriber;

class MenuController extends \MagmaCore\Administrator\Controller\AdminController
{

    use DataLayerTrait;

    /**
     * Extends the base constructor method. Which gives us access to all the base
     * methods implemented within the base controller class.
     * Class dependency can be loaded within the constructor by calling the
     * container method and passing in an associative array of dependency to use within
     * the class
     *
     * @param array $routeParams
     * @return void
     * @throws BaseInvalidArgumentException
     */
    public function __construct(array $routeParams)
    {
        parent::__construct($routeParams);
        /**
         * Dependencies are defined within a associative array like example below
         * [ roleModel => \App\Model\RoleModel::class ]. Where the key becomes the
         * property for the RoleModel object like so $this->roleModel->getRepo();
         */
        $this->addDefinitions(
            [
                'repository' => MenuModel::class,
                'commander' => MenuCommander::class,
                'column' => MenuColumn::class,
                'entity' => MenuEntity::class,
                'formMenu' => MenuForm::class,
                'menuItem' => MenuItemModel::class,
                'rawSchema' => MenuSchema::class,
                'actionEvent' => MenuActionEvent::class
            ]
        );
    }

    protected function indexAction()
    {
        $this->indexAction
            ->setAccess($this, 'can_view')
            ->execute($this, NULL, NULL, MenuSchema::class, __METHOD__)
            ->render()
            ->with()
            ->table()
            ->end();
    }

    protected function newAction()
    {
        $this->newAction
            ->setAccess($this, 'can_add')
            ->execute($this, MenuEntity::class, MenuActionSubscriber::class, MenuSchema::class, __METHOD__)
            ->render()
            ->with(
                [

                ]
            )
            ->form($this->formMenu)
            ->end();
    }

    protected function editAction()
    {
        $this->editAction
            ->setAccess($this, 'can_edit')
            ->execute($this, MenuEntity::class, MenuActionEvent::class, NULL, __METHOD__, [],
                ['item_usable' => (isset($_POST['item_usable']) && count($_POST['item_usable']) > 0 ? $_POST['item_usable'] : [])]
            )
            ->render()
            ->with(
                [
                    'parent_menu' => $this->toArray($this->findOr404()),
                    'menu_items' => $this->menuItem->getRepo()->findBy(['*'], ['item_original_id' => $this->thisRouteID(), 'item_usable' => 1]),
                ]
            )
            ->form($this->formMenu)
            ->end();
    }

    protected function quickEditAction()
    {
        $this->simpleUpdateAction
        ->setAccess($this, Access::CAN_EDIT)
        ->execute($this, MenuEntity::class, MenuActionEvent::class, NULL, __METHOD__, [], [], $this->repository)
        ->endAfterExecution();
    }

    /**
     * Route which puts the item within the trash. This is only for supported models
     * and is effectively changing the deleted_at column to 1. All datatable queries 
     * deleted_at column should be set to 0. this will prevent any trash items 
     * from showing up in the main table
     *
     * @return void
     */
    protected function trashAction()
    {

        $this->changeStatusAction
            ->setAccess($this, Access::CAN_TRASH)
            ->execute($this, MenuEntity::class, MenuActionEvent::class, NULL, __METHOD__, [], [], ['deleted_at' => 1])
            ->endAfterExecution();
    }

    /**
     * As trashing an item changes the deleted_at column to 1 we can reset that to 0
     * for individual items.
     *
     * @return void
     */
    protected function untrashAction()
    {
        $this->changeStatusAction
        ->setAccess($this, Access::CAN_UNTRASH)
        ->execute($this, MenuEntity::class, MenuActionEvent::class, NULL, __METHOD__,[], [],['deleted_at' => 0])
        ->endAfterExecution();

    }

    protected function hardDeleteAction()
    {
        $this->deleteAction
            ->setAccess($this, Access::CAN_DELETE)
            ->execute($this, NULL, MenuActionEvent::class, NULL, __METHOD__)
            ->endAfterExecution();

    }

    protected function deleteAction()
    {
        $this->deleteAction
            ->setAccess($this, Access::CAN_DELETE)
            ->execute($this, NULL, MenuActionEvent::class, NULL, __METHOD__)
            ->endAfterExecution();

    }

    /**
     * Remove a menu item from the usable list of items
     * @return bool
     */
    protected function removeItemAction(): bool
    {
        if (isset($this->formBuilder)) {
            if ($this->formBuilder->canHandleRequest()) {
                $queriedID = $this->thisRouteID() ?? null;
                $remove = $this->menuItem->getRepo()->findByIdAndUpdate(['item_usable' => NULL], $queriedID);
                if ($remove === true) {
                    $originalMenuID = $this->request->handler()->get('menu_id') ?? '/admin/menu/index';
                    $originalMenuID = (int)$originalMenuID;
                    $this->flashMessage('The item was remove from the usable list');
                    $this->redirect('/admin/menu/' . $originalMenuID . '/edit');
                }
            }
        }
        return false;
    }

    protected function toggleAction()
    {
        $this->changeStatusAction
        ->setAccess($this, Access::CAN_UNTRASH)
        ->execute($this, MenuEntity::class, MenuActionEvent::class, NULL, __METHOD__,[], [],['parent_menu' => 0])
        ->endAfterExecution();

    }

    protected function untoggleAction()
    {
        $this->changeStatusAction
        ->setAccess($this, Access::CAN_UNTRASH)
        ->execute($this, MenuEntity::class, MenuActionEvent::class, NULL, __METHOD__,[], [],['parent_menu' => 1])
        ->endAfterExecution();

    }


    protected function quickSaveAction()
    {
        if (isset($_POST['index-quick-save'])) {

        }
    }

}

