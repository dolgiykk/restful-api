<?php

namespace App\Services;

use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Contracts\Cache\LockTimeoutException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AddressService
{
    /**
     * @param Request $request
     * @param int $perPage
     * @return array<mixed>
     */
    public function getAll(Request $request, int $perPage):array
    {
        /** @var string $page */
        $page = $request->query('page') ?? '1';

        $filterParams = http_build_query($request->except('page', 'perPage'));
        $cacheKey = "addresses:page:{$page}:per_page:{$perPage}:filters:{$filterParams}";

        return Cache::tags(['addresses_list'])->rememberForever($cacheKey, function () use ($request, $perPage) {
            $addresses = Address::query()
                ->filter($request)
                ->paginate($perPage);

            return [
                [
                    'data' => AddressResource::collection($addresses)->resolve(),
                    'pagination' => [
                        'total' => $addresses->total(),
                        'per_page' => $addresses->perPage(),
                        'current_page' => $addresses->currentPage(),
                        'last_page' => $addresses->lastPage(),
                        'next_page_url' => $addresses->nextPageUrl(),
                        'prev_page_url' => $addresses->previousPageUrl(),
                    ],
                ],
                ResponseAlias::HTTP_OK,
            ];
        });
    }

    /**
     * @param int $id
     * @return array<mixed>
     */
    public function getOne(int $id): array
    {
        /** @var array<mixed> */
        return Cache::rememberForever("address:{$id}", function () use ($id) {
            $address = Address::find($id);

            if (! $address) {
                return [
                    ['message'=> __('addresses.not_found')],
                    ResponseAlias::HTTP_NOT_FOUND,
                ];
            }

            return [
                ['data'=> new AddressResource($address)],
                ResponseAlias::HTTP_OK,
            ];
        });
    }

    /**
     * @param array<string, mixed> $data
     * @return array<mixed>
     */
    public function createAddress(array $data): array
    {
        $address = Address::create($data);

        Cache::tags(['addresses_list'])->flush();

        return [
            [
                'message'=> __('actions.created_success'),
                'data'=> new AddressResource($address),
            ],
            ResponseAlias::HTTP_CREATED,
        ];
    }

    /**
     * @param array<string, mixed> $data
     * @param int $id
     * @return array<mixed>
     */
    public function updateAddress(array $data, int $id): array
    {
        $address = Address::find($id);

        if (! $address) {
            return [
                ['message'=> __('address not found')],
                ResponseAlias::HTTP_NOT_FOUND,
            ];
        }

        if (! $address->update($data)) {
            return [
                ['message'=> __('actions.update_failed')],
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
            ];
        }

        Cache::lock('cache_update_lock', 10)->block(0, function () use ($id) {
            Cache::tags(['addresses_list'])->flush();
            Cache::forget("address:{$id}");
        });

        return [
            [
                'message'=> __('actions.updated_success'),
                'data'=> new AddressResource($address),
            ],
            ResponseAlias::HTTP_OK,
        ];
    }

    /**
     * @param int $id
     * @return array<mixed>
     * @throws LockTimeoutException
     */
    public function deleteAddress(int $id): array
    {
        $address = Address::find($id);

        if (! $address) {
            return [
                ['message'=> __('address.not_found')],
                ResponseAlias::HTTP_NOT_FOUND,
            ];
        }

        if (! $address->delete()) {
            return [
                ['message'=> __('actions.delete_failed')],
                ResponseAlias::HTTP_SERVICE_UNAVAILABLE,
            ];
        }

        Cache::lock('cache_update_lock', 10)->block(0, function () use ($id) {
            Cache::tags(['addresses_list'])->flush();
            Cache::forget("address:{$id}");
        });

        return [
            ['message'=> __('actions.deleted_success')],
            ResponseAlias::HTTP_OK,
        ];
    }
}
