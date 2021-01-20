<?php


namespace MyCandies\Entities;


class ActivePrincipleSideEffect {
    public const ACTIVE_PRINCIPLE_EFFECT = 1;

    private $active_principle_id;
    private $side_effect_id;

    public function __construct(int $source, array $data=[])
    {
        if ($source === self::ACTIVE_PRINCIPLE_EFFECT) {
            $this->active_principle_id = $data['active_principle_id'];
            $this->side_effect_id = $data['side_effect_id'];
        }
    }

    public function getActive_principle_id() : int {
        return $this->active_principle_id;
    }

    public function getSide_effect_id() : int {
        return $this->side_effect_id;
    }

    public function getValues() : array {
        $fields = [];
        foreach ($this as $key => $value) {
            $fields[$key] = $value;
        }
        return $fields;
    }
}
