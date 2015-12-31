<?php
namespace Spider\Commands\Languages\OrientSQL;
use Spider\Commands\Bag;
use Spider\Commands\Command;
use Spider\Commands\Languages\AbstractProcessor;
use Spider\Commands\Languages\ProcessorInterface;

/**
 * Class AbstractOrientSqlProcessor
 * @package Spider\Commands\Languages\OrientSQL
 */
abstract class AbstractOrientSqlProcessor extends AbstractProcessor
{
    protected $bag;

    /**
     * A map of operators from the Command Bag to Orient SQL
     * @var array
     */
    public $operatorsMap = [
        Bag::COMPARATOR_EQUAL => '=',
        Bag::COMPARATOR_GT => '>',
        Bag::COMPARATOR_LT => '<',
        Bag::COMPARATOR_LE => '<=',
        Bag::COMPARATOR_GE => '>=',
        Bag::COMPARATOR_NE => '<>',

        Bag::CONJUNCTION_AND => 'AND',
        Bag::CONJUNCTION_OR => 'OR',

        Bag::ORDER_DESC => 'DESC',
        Bag::ORDER_ASC => 'ASC',
    ];

    protected $processor;

    public function __construct(CommandProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * Initialize the Command Processor
     * @param Bag $bag
     */
    public function init(Bag $bag)
    {
        $this->bag = $bag;
    }

    /**
     * Begin the current script without a space
     * @param string $clause
     * @return string
     */
    public function startScript($clause, &$script)
    {
        $script .= (string) $clause;
        return $script;
    }

    /**
     * Add to the current script with a space before
     * @param $clause
     * @return string
     * @throws \Exception
     */
    public function addToScript($clause, &$script)
    {
        if (!is_string($clause)) {
            throw new \Exception("Only strings can be added to script");
        }

        $script .= " " . $clause;
        return $script;
    }

    /**
     * Append target to current script
     * @param string $prefix
     * @param $script
     * @throws \Exception
     */
    protected function appendTarget($prefix = "from", &$script)
    {
        // Set the Default
        $target = 'V';

        // Search through wheres for label (class) or id
        foreach ($this->bag->where as $index => $value) {
            if ($value[0] === Bag::ELEMENT_LABEL || $value[0] === Bag::ELEMENT_ID) {
                $target = $value[2];
                unset($this->bag->where[$index]);
                $this->bag->where = array_values($this->bag->where);
            }
        }

        if ($prefix !== "") {
            $this->addToScript(strtoupper($prefix), $script); // FROM
        }
        $this->addToScript($target, $script); // ID or Class
    }

    /**
     * Cast a value from the Command Bag to one
     * usable by Orient SQL (a string)
     * @param $value
     * @return string
     */
    protected function castValue($value)
    {
        if ($value === true) {
            $value = 'true';

        } elseif ($value === false) {
            $value = 'false';

        } elseif (is_string($value)) {
            $value = "'$value'";
        }

        return (string)$value;
    }

    /**
     * Map a Command Bag operator to its Orient SQL counterpart
     * @param $operator
     * @return mixed
     */
    public function toSqlOperator($operator)
    {
        return $this->operatorsMap[$operator];
    }

    /**
     * @param $script
     * @return Command
     */
    protected function createCommand($script)
    {
        $command = new Command($script);
        $command->setScriptLanguage('orientSQL');
        return $command;
    }
}
