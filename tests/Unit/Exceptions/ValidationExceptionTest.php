<?php

declare(strict_types=1);

use AustinW\UsaGym\Exceptions\ValidationException;
use AustinW\UsaGym\Exceptions\UsaGymException;
use AustinW\UsaGym\UsaGym;
use AustinW\UsaGym\Requests\TestRequest;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;

describe('ValidationException', function () {
    describe('instantiation', function () {
        it('can be instantiated with only a message', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception->getMessage())->toBe('Validation failed');
            expect($exception->getCode())->toBe(422);
            expect($exception->getResponse())->toBeNull();
            expect($exception->getData())->toBeNull();
            expect($exception->errors())->toBe([]);
        });

        it('can be instantiated with all parameters', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Validation failed'], 422),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'name' => ['The name field must be at least 2 characters.'],
                ],
            ];
            $previous = new Exception('Previous exception');

            $exception = new ValidationException(
                message: 'Validation failed',
                response: $response,
                data: $data,
                code: 422,
                previous: $previous
            );

            expect($exception->getMessage())->toBe('Validation failed');
            expect($exception->getCode())->toBe(422);
            expect($exception->getResponse())->toBe($response);
            expect($exception->getData())->toBe($data);
            expect($exception->getPrevious())->toBe($previous);
            expect($exception->errors())->toBe($data['errors']);
        });

        it('uses custom code when provided', function () {
            $exception = new ValidationException('Validation failed', null, null, 400);

            expect($exception->getCode())->toBe(400);
        });

        it('extends UsaGymException', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception)->toBeInstanceOf(UsaGymException::class);
        });

        it('extends PHP Exception class', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception)->toBeInstanceOf(Exception::class);
        });
    });

    describe('errors()', function () {
        it('returns empty array when no data is provided', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception->errors())->toBe([]);
        });

        it('returns empty array when data has no errors key', function () {
            $data = ['message' => 'Validation failed'];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->errors())->toBe([]);
        });

        it('returns errors from data.errors key', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['The email field is required.', 'The email must be valid.'],
                    'password' => ['The password must be at least 8 characters.'],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->errors())->toBe($data['errors']);
            expect($exception->errors()['email'])->toBe(['The email field is required.', 'The email must be valid.']);
            expect($exception->errors()['password'])->toBe(['The password must be at least 8 characters.']);
        });

        it('returns errors from data.data.errors key (nested format)', function () {
            $data = [
                'message' => 'Validation failed',
                'data' => [
                    'errors' => [
                        'username' => ['The username is already taken.'],
                    ],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->errors())->toBe(['username' => ['The username is already taken.']]);
        });

        it('prefers data.errors over data.data.errors when both exist', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'field1' => ['Top level error'],
                ],
                'data' => [
                    'errors' => [
                        'field2' => ['Nested error'],
                    ],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->errors())->toBe(['field1' => ['Top level error']]);
        });

        it('returns multiple errors for a single field', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'password' => [
                        'The password must be at least 8 characters.',
                        'The password must contain at least one uppercase letter.',
                        'The password must contain at least one number.',
                    ],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->errors()['password'])->toHaveCount(3);
        });
    });

    describe('hasError()', function () {
        it('returns false when no errors exist', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception->hasError('email'))->toBeFalse();
            expect($exception->hasError('password'))->toBeFalse();
        });

        it('returns false for non-existent field', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['The email field is required.'],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->hasError('password'))->toBeFalse();
            expect($exception->hasError('name'))->toBeFalse();
        });

        it('returns true for existing field with errors', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->hasError('email'))->toBeTrue();
            expect($exception->hasError('password'))->toBeTrue();
        });

        it('returns true for field with empty error array', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'email' => [],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            // isset returns true even for empty array
            expect($exception->hasError('email'))->toBeTrue();
        });
    });

    describe('getFieldErrors()', function () {
        it('returns empty array when no errors exist', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception->getFieldErrors('email'))->toBe([]);
        });

        it('returns empty array for non-existent field', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['The email field is required.'],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->getFieldErrors('password'))->toBe([]);
            expect($exception->getFieldErrors('name'))->toBe([]);
        });

        it('returns errors for existing field', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'email' => ['The email field is required.', 'The email must be valid.'],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->getFieldErrors('email'))->toBe([
                'The email field is required.',
                'The email must be valid.',
            ]);
        });

        it('returns single error for field with one error', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'name' => ['The name field is required.'],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->getFieldErrors('name'))->toBe(['The name field is required.']);
            expect($exception->getFieldErrors('name'))->toHaveCount(1);
        });

        it('returns multiple errors for field with multiple errors', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => [
                    'password' => [
                        'The password must be at least 8 characters.',
                        'The password must contain at least one uppercase letter.',
                        'The password must contain at least one number.',
                        'The password must contain at least one special character.',
                    ],
                ],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            $errors = $exception->getFieldErrors('password');
            expect($errors)->toHaveCount(4);
            expect($errors[0])->toBe('The password must be at least 8 characters.');
            expect($errors[3])->toBe('The password must contain at least one special character.');
        });
    });

    describe('getResponse()', function () {
        it('returns null when no response is provided', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception->getResponse())->toBeNull();
        });

        it('returns the Saloon response when provided', function () {
            $connector = new UsaGym('test-user', 'test-pass');
            $mockClient = new MockClient([
                TestRequest::class => MockResponse::make(['message' => 'Validation failed'], 422),
            ]);
            $connector->withMockClient($mockClient);

            $response = $connector->send(new TestRequest());
            $exception = new ValidationException('Validation failed', $response);

            expect($exception->getResponse())->toBe($response);
            expect($exception->getResponse()->status())->toBe(422);
        });
    });

    describe('getData()', function () {
        it('returns null when no data is provided', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception->getData())->toBeNull();
        });

        it('returns the data array when provided', function () {
            $data = [
                'message' => 'Validation failed',
                'errors' => ['email' => ['Invalid email']],
            ];
            $exception = new ValidationException('Validation failed', null, $data);

            expect($exception->getData())->toBe($data);
        });
    });

    describe('getMessage()', function () {
        it('returns the exception message', function () {
            $exception = new ValidationException('The given data was invalid.');

            expect($exception->getMessage())->toBe('The given data was invalid.');
        });
    });

    describe('getCode()', function () {
        it('returns 422 by default', function () {
            $exception = new ValidationException('Validation failed');

            expect($exception->getCode())->toBe(422);
        });

        it('returns custom code when provided', function () {
            $exception = new ValidationException('Validation failed', null, null, 400);

            expect($exception->getCode())->toBe(400);
        });
    });

    describe('common validation scenarios', function () {
        it('handles required field validation', function () {
            $data = [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'first_name' => ['The first name field is required.'],
                    'last_name' => ['The last name field is required.'],
                    'email' => ['The email field is required.'],
                ],
            ];
            $exception = new ValidationException('The given data was invalid.', null, $data);

            expect($exception->hasError('first_name'))->toBeTrue();
            expect($exception->hasError('last_name'))->toBeTrue();
            expect($exception->hasError('email'))->toBeTrue();
            expect($exception->errors())->toHaveCount(3);
        });

        it('handles format validation errors', function () {
            $data = [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email must be a valid email address.'],
                    'phone' => ['The phone format is invalid.'],
                    'date_of_birth' => ['The date of birth is not a valid date.'],
                ],
            ];
            $exception = new ValidationException('The given data was invalid.', null, $data);

            expect($exception->getFieldErrors('email')[0])->toBe('The email must be a valid email address.');
            expect($exception->getFieldErrors('phone')[0])->toBe('The phone format is invalid.');
            expect($exception->getFieldErrors('date_of_birth')[0])->toBe('The date of birth is not a valid date.');
        });

        it('handles unique constraint validation errors', function () {
            $data = [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                    'member_id' => ['The member ID already exists.'],
                ],
            ];
            $exception = new ValidationException('The given data was invalid.', null, $data);

            expect($exception->getFieldErrors('email')[0])->toBe('The email has already been taken.');
            expect($exception->getFieldErrors('member_id')[0])->toBe('The member ID already exists.');
        });

        it('handles nested field validation errors', function () {
            $data = [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'address.street' => ['The street field is required.'],
                    'address.city' => ['The city field is required.'],
                    'address.zip' => ['The zip code must be 5 digits.'],
                ],
            ];
            $exception = new ValidationException('The given data was invalid.', null, $data);

            expect($exception->hasError('address.street'))->toBeTrue();
            expect($exception->hasError('address.city'))->toBeTrue();
            expect($exception->getFieldErrors('address.zip')[0])->toBe('The zip code must be 5 digits.');
        });

        it('handles array field validation errors', function () {
            $data = [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'athletes.0.name' => ['The athlete name is required.'],
                    'athletes.1.date_of_birth' => ['The date of birth must be a valid date.'],
                    'athletes.2.level' => ['The selected level is invalid.'],
                ],
            ];
            $exception = new ValidationException('The given data was invalid.', null, $data);

            expect($exception->hasError('athletes.0.name'))->toBeTrue();
            expect($exception->hasError('athletes.1.date_of_birth'))->toBeTrue();
            expect($exception->hasError('athletes.2.level'))->toBeTrue();
        });

        it('handles gymnastics-specific validation errors', function () {
            $data = [
                'message' => 'The given data was invalid.',
                'errors' => [
                    'discipline' => ['The selected discipline is invalid.'],
                    'level' => ['The level must be between 1 and 10.'],
                    'sanction_id' => ['The sanction ID does not exist.'],
                    'usag_member_id' => ['The USAG member ID format is invalid.'],
                ],
            ];
            $exception = new ValidationException('The given data was invalid.', null, $data);

            expect($exception->errors())->toHaveCount(4);
            expect($exception->getFieldErrors('discipline')[0])->toBe('The selected discipline is invalid.');
            expect($exception->getFieldErrors('usag_member_id')[0])->toBe('The USAG member ID format is invalid.');
        });
    });
});
