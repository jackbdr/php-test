<?php

namespace Tests\DataProviders;

trait TaskDataProviders
{
    public static function indexRequestProvider(): array
    {
        return [
            'without deleted' => [false, 2],
            'with deleted'    => [true, 3],
        ];
    }

    public static function storeRequestProvider(): array
    {
        return [
            'Name invalid'    => [
                'ab',
                'Valid description',
                [
                    'name' => 'The name of the task must be at least 3 characters.'
                ]
            ],
            'Description invalid'    => [
                'Task name',
                'ab',
                [
                    'description' => 'The task description must be at least 10 characters.'
                ]
            ],
            'Both name and description invalid'    => [
                'ab',
                'ab',
                [
                    'name' => 'The name of the task must be at least 3 characters.',
                    'description' => 'The task description must be at least 10 characters.'
                ]
            ],
        ];
    }
}
