<?php

use App\Enums\TaskStatusEnum;
use App\Models\Task;
use function Pest\Laravel\{getJson, postJson, putJson, deleteJson};

beforeEach(function () {
    Task::factory(100)->create();
});

test('index', function () {
    $response = getJson(route('tasks.index'));
    $response->assertOk();
    $response->assertJsonCount(100, 'data');
    $response->assertJsonStructure([
        'data' => [
            [
                'id',
                'title',
                'description',
                'status',
                'status_label',
                'created_at',
                'updated_at',
            ],
        ],
    ]);
});

test('store', function (array $body, int $httpStatus) {
    $response = postJson(route('tasks.store'), $body);
    $response->assertStatus($httpStatus);
    if ($httpStatus === 201) {
        expect($response->collect('data.status')->first())->toBe(\App\Enums\TaskStatusEnum::New->value);
    }
})
->with([
    'success' => [
        'body' => [
            'title' => fake()->word(),
            'description' => fake()->text(),
        ],
        'httpStatus' => 201
    ],
    'error' => [
        'body' => [
            'description' => fake()->text(),
        ],
        'httpStatus' => 422
    ]
]);

test('show', function (int $id, int $httpStatus) {
    $response = getJson(route('tasks.show', $id));
    $response->assertStatus($httpStatus);
})
->with([
    'success' => [
        'id' => 1,
        'httpStatus' => 200,
    ],
    'error' => [
        'id' => 101,
        'httpStatus' => 404,
    ],
]);

test('update', function (array $body, int $httpStatus) {
    $task = Task::factory()->create([
        'status' => TaskStatusEnum::New,
    ]);
    $currentTitle = $task->title;
    $currentStatus = $task->status;

    $response = putJson(route('tasks.update', $task->getKey()), $body);
    $response->assertStatus($httpStatus);

    if ($httpStatus === 200) {
        $task->refresh();

        expect($currentTitle)->not->toBe($task->title)
            ->and($currentStatus)->not->toBe($task->status);
    }
})
->with([
    'success' => fn() => [
        'body' => [
            'title' => fake()->word(),
            'description' => fake()->text(),
            'status' => TaskStatusEnum::InProgress->value,
        ],
        'httpStatus' => 200,
    ],
    'error' => fn() => [
        'body' => [
            'title' => fake()->word(),
            'description' => fake()->text(),
            'status' => TaskStatusEnum::New->value,
        ],
        'httpStatus' => 422,
    ],
]);

test('destroy', function () {
    $id = Task::inRandomOrder()->value('id');
    $response = deleteJson(route('tasks.destroy', $id));
    $response->assertNoContent();
    expect(Task::count())->toBe(99);
});
