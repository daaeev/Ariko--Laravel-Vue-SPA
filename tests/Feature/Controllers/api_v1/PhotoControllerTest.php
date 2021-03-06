<?php

namespace Tests\Feature\Controllers;

use App\Http\Requests\PaginationData;
use App\Models\Image;
use App\Models\PhotoWork;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class PhotoControllerTest extends TestCase
{
    public function testPhotosList()
    {
        $result = [
            'some_pag_data1' => 1,
            'some_pag_data2' => 2,
            'some_pag_data3' => 3,
            'data' => [],
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'paginate', 'whereHas'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('images')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('whereHas')
            ->with('images')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('paginate')
            ->with(null)
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(PhotoWork::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $request_mock = $this->getMockBuilder(PaginationData::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->with('_limit')
            ->willReturn(null);

        $this->app->instance(
            PaginationData::class,
            $request_mock
        );

        $this->get(route('works.photos'))
            ->assertOk()
            ->assertJson($result);
    }

    public function testPhotosListWithPerPageVar()
    {
        $perPage = 6;
        $result = [
            'some_pag_data1' => 1,
            'some_pag_data2' => 2,
            'some_pag_data3' => 3,
            'data' => [],
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'paginate', 'whereHas'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('whereHas')
            ->with('images')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('images')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('paginate')
            ->with($perPage)
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(PhotoWork::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $request_mock = $this->getMockBuilder(PaginationData::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->with('_limit')
            ->willReturn($perPage);

        $this->app->instance(
            PaginationData::class,
            $request_mock
        );

        $this->get(route('works.photos', ['_limit' => $perPage]))
            ->assertOk()
            ->assertJson($result);
    }

    public function testPhotoWorkSingleIfExist()
    {
        $work_id = 1;
        $result = [
            'data1' => 1,
            'data2' => 2,
            'data3' => 3,
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'where', 'firstOrFail'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('images')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('id', $work_id)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('firstOrFail')
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(PhotoWork::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('works.photos.single', ['work_id' => $work_id]))
            ->assertOk()
            ->assertJson($result);
    }

    public function testPhotoWorkSingleIfNotExist()
    {
        $work_id = 1;

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['with', 'where', 'firstOrFail'])
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('with')
            ->with('images')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('id', $work_id)
            ->willReturn($builder_mock);

        $builder_mock->expects($this->once())
            ->method('firstOrFail')
            ->willThrowException(new ModelNotFoundException('', 404));

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(PhotoWork::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('works.photos.single', ['work_id' => $work_id]))
            ->assertStatus(404);
    }

    public function testPhotoWorkNextPrevIds()
    {
        $work_id = 2;

        $result1 = [
            'data1' => 1
        ];

        $result2 = [
            'data2' => 3
        ];

        $result = [
            'next' => $result1,
            'prev' => $result2,
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['where', 'first'])
            ->addMethods(['select', 'orderBy'])
            ->getMock();

        $builder_mock->expects($this->exactly(2))
            ->method('select')
            ->with('id')
            ->willReturn($builder_mock);

        $builder_mock->expects($this->exactly(2))
            ->method('where')
            ->withConsecutive(
                [[['id', '>', $work_id]]],
                [[['id', '<', $work_id]]]
            )
            ->willReturnSelf();

        $builder_mock->expects($this->exactly(2))
            ->method('first')
            ->willReturnOnConsecutiveCalls($result1, $result2);

        $builder_mock->expects($this->once())
            ->method('orderBy')
            ->with('id', 'desc')
            ->willReturn($builder_mock);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->exactly(2))
            ->method('queryBuilder')
            ->with(PhotoWork::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->get(route('works.photos.next/prev', ['work_id' => $work_id]))
            ->assertOk()
            ->assertJson($result);
    }
}
