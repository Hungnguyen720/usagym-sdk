<?php

declare(strict_types=1);

use AustinW\UsaGym\Exceptions\NotFoundException;
use AustinW\UsaGym\Exceptions\UsaGymException;
use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Requests\TestRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('NotFoundException', function () {
    describe('instantiation', function () {
        it('can be instantiated with default values', function () {
            $exception = new NotFoundException();

            expect($exception->getMessage())->toBe('The resource you are looking for could not be found.');
            expect($exception->getCode())->toBe(404);
            expect($exception->getResponse())->toBeNull();
            expect($exception->getData())->toBeNull();
        });

        it('can be instantiated with custom message', function () {
            $exception = new NotFoundException('User not found');

            expect($exception->getMessage())->toBe('User not found');
            expect($exception->getCode())->toBe(404);
        });

        it('can be instantiated with all parameters', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Not found'], 404),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $data = ['message' => 'Resource not found', 'resource_type' => 'user', 'resource_id' => 123];
            $previous = new Exception('Previous exception');

            $exception = new NotFoundException(
                message: 'User with ID 123 not found',
                response: $response,
                data: $data,
                code: 404,
                previous: $previous
            );

            expect($exception->getMessage())->toBe('User with ID 123 not found');
            expect($exception->getCode())->toBe(404);
            expect($exception->getResponse())->toBe($response);
            expect($exception->getData())->toBe($data);
            expect($exception->getPrevious())->toBe($previous);
        });

        it('uses custom code when provided', function () {
            $exception = new NotFoundException('Not found', null, null, 410);

            expect($exception->getCode())->toBe(410);
        });

        it('extends UsaGymException', function () {
            $exception = new NotFoundException();

            expect($exception)->toBeInstanceOf(UsaGymException::class);
        });

        it('extends PHP Exception class', function () {
            $exception = new NotFoundException();

            expect($exception)->toBeInstanceOf(Exception::class);
        });
    });

    describe('getResponse()', function () {
        it('returns null when no response is provided', function () {
            $exception = new NotFoundException('Resource not found');

            expect($exception->getResponse())->toBeNull();
        });

        it('returns the Saloon response when provided', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Not found'], 404),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new NotFoundException('Resource not found', $response);

            expect($exception->getResponse())->toBe($response);
            expect($exception->getResponse()->status())->toBe(404);
        });
    });

    describe('getData()', function () {
        it('returns null when no data is provided', function () {
            $exception = new NotFoundException('Resource not found');

            expect($exception->getData())->toBeNull();
        });

        it('returns the data array when provided', function () {
            $data = [
                'message' => 'Athlete not found',
                'resource_type' => 'athlete',
                'resource_id' => 456,
            ];
            $exception = new NotFoundException('Athlete not found', null, $data);

            expect($exception->getData())->toBe($data);
            expect($exception->getData()['resource_type'])->toBe('athlete');
            expect($exception->getData()['resource_id'])->toBe(456);
        });
    });

    describe('getMessage()', function () {
        it('returns default message when none provided', function () {
            $exception = new NotFoundException();

            expect($exception->getMessage())->toBe('The resource you are looking for could not be found.');
        });

        it('returns custom message when provided', function () {
            $exception = new NotFoundException('Competition with ID 789 not found');

            expect($exception->getMessage())->toBe('Competition with ID 789 not found');
        });
    });

    describe('getCode()', function () {
        it('returns 404 by default', function () {
            $exception = new NotFoundException();

            expect($exception->getCode())->toBe(404);
        });

        it('returns 404 when message is provided but code is not', function () {
            $exception = new NotFoundException('Resource not found');

            expect($exception->getCode())->toBe(404);
        });

        it('returns custom code when provided', function () {
            $exception = new NotFoundException('Gone', null, null, 410);

            expect($exception->getCode())->toBe(410);
        });
    });

    describe('common not found scenarios', function () {
        it('handles athlete not found error', function () {
            $data = [
                'message' => 'Athlete not found',
                'resource' => 'athlete',
                'id' => 12345,
            ];
            $exception = new NotFoundException(
                'Athlete not found',
                null,
                $data,
                404
            );

            expect($exception->getMessage())->toBe('Athlete not found');
            expect($exception->getCode())->toBe(404);
            expect($exception->getData()['resource'])->toBe('athlete');
            expect($exception->getData()['id'])->toBe(12345);
        });

        it('handles competition not found error', function () {
            $data = [
                'message' => 'Competition not found',
                'resource' => 'competition',
                'sanction_id' => 98765,
            ];
            $exception = new NotFoundException(
                'Competition not found',
                null,
                $data,
                404
            );

            expect($exception->getMessage())->toBe('Competition not found');
            expect($exception->getData()['sanction_id'])->toBe(98765);
        });

        it('handles club not found error', function () {
            $data = [
                'message' => 'Club not found',
                'resource' => 'club',
                'club_id' => 'CLUB001',
            ];
            $exception = new NotFoundException(
                'Club not found',
                null,
                $data,
                404
            );

            expect($exception->getMessage())->toBe('Club not found');
            expect($exception->getData()['club_id'])->toBe('CLUB001');
        });

        it('handles discipline not found error', function () {
            $data = [
                'message' => 'Discipline not found',
                'resource' => 'discipline',
                'discipline_code' => 'WAG',
            ];
            $exception = new NotFoundException(
                'Discipline not found',
                null,
                $data,
                404
            );

            expect($exception->getMessage())->toBe('Discipline not found');
            expect($exception->getData()['discipline_code'])->toBe('WAG');
        });

        it('handles event not found error', function () {
            $data = [
                'message' => 'Event not found',
                'resource' => 'event',
                'event_id' => 'EVT-2024-001',
            ];
            $exception = new NotFoundException(
                'Event not found',
                null,
                $data,
                404
            );

            expect($exception->getMessage())->toBe('Event not found');
            expect($exception->getData()['event_id'])->toBe('EVT-2024-001');
        });
    });
});
