<?php

namespace Laravel\Scout\Tests\Integration;

use Orchestra\Testbench\Attributes\RequiresEnv;
use Workbench\App\Models\SearchableUser;

/**
 * @group algolia
 * @group external-network
 */
#[RequiresEnv('ALGOLIA_APP_ID')]
class AlgoliaSearchableTest extends TestCase
{
    use SearchableTests;

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function defineEnvironment($app)
    {
        $this->defineScoutEnvironment($app);
    }

    /**
     * Define database migrations.
     *
     * @return void
     */
    protected function defineDatabaseMigrations()
    {
        $this->defineScoutDatabaseMigrations();
    }

    /**
     * Perform any work that should take place once the database has finished refreshing.
     *
     * @return void
     */
    protected function afterRefreshingDatabase()
    {
        $this->importScoutIndexFrom(SearchableUser::class);
    }

    public function test_it_can_use_basic_search()
    {
        $results = $this->itCanUseBasicSearch();

        $this->assertSame([
            11 => 'Larry Casper',
            1 => 'Laravel Framework',
            44 => 'Amos Larson Sr.',
            43 => 'Dana Larson Sr.',
            42 => 'Dax Larkin',
            41 => 'Gudrun Larkin',
            40 => 'Otis Larson MD',
            39 => 'Linkwood Larkin',
            20 => 'Prof. Larry Prosacco DVM',
            12 => 'Reta Larkin',
        ], $results->pluck('name', 'id')->all());
    }

    public function test_it_can_use_basic_search_with_query_callback()
    {
        $results = $this->itCanUseBasicSearchWithQueryCallback();

        $this->assertSame([
            1 => 'Laravel Framework',
            44 => 'Amos Larson Sr.',
            43 => 'Dana Larson Sr.',
            42 => 'Dax Larkin',
            41 => 'Gudrun Larkin',
            40 => 'Otis Larson MD',
            12 => 'Reta Larkin',
        ], $results->pluck('name', 'id')->all());
    }

    public function test_it_can_use_basic_search_to_fetch_keys()
    {
        $results = $this->itCanUseBasicSearchToFetchKeys();

        $this->assertSame([
            '11',
            '1',
            '44',
            '43',
            '42',
            '41',
            '40',
            '39',
            '20',
            '12',
        ], $results->all());
    }

    public function test_it_can_use_basic_search_with_query_callback_to_fetch_keys()
    {
        $results = $this->itCanUseBasicSearchWithQueryCallbackToFetchKeys();

        $this->assertSame([
            '11',
            '1',
            '44',
            '43',
            '42',
            '41',
            '40',
            '39',
            '20',
            '12',
        ], $results->all());
    }

    public function test_it_return_same_keys_with_query_callback()
    {
        $this->assertSame(
            $this->itCanUseBasicSearchToFetchKeys()->all(),
            $this->itCanUseBasicSearchWithQueryCallbackToFetchKeys()->all()
        );
    }

    public function test_it_can_use_paginated_search()
    {
        [$page1, $page2] = $this->itCanUsePaginatedSearch();

        $this->assertSame([
            11 => 'Larry Casper',
            1 => 'Laravel Framework',
            44 => 'Amos Larson Sr.',
            43 => 'Dana Larson Sr.',
            42 => 'Dax Larkin',
        ], $page1->pluck('name', 'id')->all());

        $this->assertSame([
            41 => 'Gudrun Larkin',
            40 => 'Otis Larson MD',
            39 => 'Linkwood Larkin',
            20 => 'Prof. Larry Prosacco DVM',
            12 => 'Reta Larkin',
        ], $page2->pluck('name', 'id')->all());
    }

    public function test_it_can_use_paginated_search_with_query_callback()
    {
        [$page1, $page2] = $this->itCanUsePaginatedSearchWithQueryCallback();

        $this->assertSame([
            1 => 'Laravel Framework',
            44 => 'Amos Larson Sr.',
            43 => 'Dana Larson Sr.',
            42 => 'Dax Larkin',
        ], $page1->pluck('name', 'id')->all());

        $this->assertSame([
            41 => 'Gudrun Larkin',
            40 => 'Otis Larson MD',
            12 => 'Reta Larkin',
        ], $page2->pluck('name', 'id')->all());
    }

    public function test_it_can_use_paginated_search_with_after_raw_search_callback()
    {
        $rawResults = $this->itCanAccessRawSearchResultsOfPaginateUsingAfterRawSearchCallback();

        $this->assertIsArray($rawResults);
        $this->assertArrayHasKey('hits', $rawResults);
        $this->assertArrayHasKey('processingTimeMS', $rawResults);
    }

    public function test_it_can_use_raw_paginated_search_with_after_raw_search_callback()
    {
        $rawResults = $this->itCanAccessRawSearchResultsOfPaginateRawUsingAfterRawSearchCallback();

        $this->assertIsArray($rawResults);
        $this->assertArrayHasKey('hits', $rawResults);
        $this->assertArrayHasKey('processingTimeMS', $rawResults);
    }

    public function test_it_can_use_simple_paginated_search_with_after_raw_search_callback()
    {
        $rawResults = $this->itCanAccessRawSearchResultsOfSimplePaginateUsingAfterRawSearchCallback();

        $this->assertIsArray($rawResults);
        $this->assertArrayHasKey('hits', $rawResults);
        $this->assertArrayHasKey('processingTimeMS', $rawResults);
    }

    public function test_it_can_use_raw_simple_paginated_search_with_after_raw_search_callback()
    {
        $rawResults = $this->itCanAccessRawSearchResultsOfSimplePaginateRawUsingAfterRawSearchCallback();

        $this->assertIsArray($rawResults);
        $this->assertArrayHasKey('hits', $rawResults);
        $this->assertArrayHasKey('processingTimeMS', $rawResults);
    }

    public function test_it_can_use_raw_get_search_with_after_raw_search_callback()
    {
        $rawResults = $this->itCanAccessRawSearchResultsOfGetUsingAfterRawSearchCallback();

        $this->assertIsArray($rawResults);
        $this->assertArrayHasKey('hits', $rawResults);
        $this->assertArrayHasKey('processingTimeMS', $rawResults);
    }

    public function test_it_can_use_raw_cursor_search_with_after_raw_search_callback()
    {
        $rawResults = $this->itCanAccessRawSearchResultsOfCursorUsingAfterRawSearchCallback();

        $this->assertIsArray($rawResults);
        $this->assertArrayHasKey('hits', $rawResults);
        $this->assertArrayHasKey('processingTimeMS', $rawResults);
    }

    protected static function scoutDriver(): string
    {
        return 'algolia';
    }
}
