<?php
declare(strict_types=1);

namespace IkonizerCore\FormBuilder\EventSubscriber;

use JetBrains\PhpStorm\ArrayShape;
use IkonizerCore\EventDispatcher\EventDispatcherTrait;
use IkonizerCore\EventDispatcher\EventDispatcherDefaulter;
use IkonizerCore\EventDispatcher\EventSubscriberInterface;
use IkonizerCore\ValidationRule\Event\ValidateRuleEvent;

/**
 * Note: If we want to flash other routes then they must be declared within the ACTION_ROUTES
 * protected constant
 */
class FormBuilderValidationSubscriber extends EventDispatcherDefaulter implements EventSubscriberInterface
{

    use EventDispatcherTrait;

    /**
     * Subscribe multiple listeners to listen for the NewActionEvent. This will fire
     * each time a new user is added to the database. Listeners can then perform
     * additional tasks on that return object.
     *
     * @return array
     */
    #[ArrayShape([ValidateRuleEvent::NAME => "\string[][]"])] public static function getSubscribedEvents(): array
    {
        return [
            ValidateRuleEvent::NAME => [
                ['test'],
            ]
        ];
    }


}
