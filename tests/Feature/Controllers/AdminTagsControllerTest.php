<?php

namespace Tests\Feature\Controllers;

use App\Http\Middleware\AuthWithToken;
use App\Http\Requests\AddTagToPost;
use App\Models\PostTag;
use App\Models\Tag;
use App\Services\TestHelpers\GetModelQueryBuilder;
use App\Services\TestHelpers\interfaces\GetModelQueryBuilderInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminTagsControllerTest extends TestCase
{
    public function testTagsList()
    {
        $result = [
            'pag_data1',
            'pag_data2',
            'pag_data3',
            'db_data',
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('get')
            ->willReturn($result);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Tag::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $this->withoutMiddleware(AuthWithToken::class)
            ->get(route('tags.list'))
            ->assertOk()
            ->assertJson($result);
    }

    public function testAddTagIfTagNotExists()
    {
        $data = [
            'tag' => 'undefined tag',
            'post_id' => 1
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['where', 'first'])
            ->addMethods(['select'])
            ->disableOriginalConstructor()
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('id')
            ->willReturnSelf();

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('name', $data['tag'])
            ->willReturnSelf();

        $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn(null);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Tag::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $tag_model_mock = $this->getMockBuilder(Tag::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $tag_model_mock->expects($this->once())
            ->method('fill')
            ->with(['name' => $data['tag']]);

        $tag_model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $tag_model_mock->id = 2;

        $this->instance(
            Tag::class,
            $tag_model_mock
        );

        $link_model_mock = $this->getMockBuilder(PostTag::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $link_model_mock->expects($this->once())
            ->method('fill')
            ->with(['tag_id' => $tag_model_mock->id, 'post_id' => 1]);

        $link_model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $link_model_mock->tag_id = $tag_model_mock->id;
        $link_model_mock->post_id = 1;
        $link_model_mock->id = 1;

        $this->instance(PostTag::class, $link_model_mock);

        $request_mock = $this->getMockBuilder(AddTagToPost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->instance(AddTagToPost::class, $request_mock);

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(\route('tags.add-to-post'), $data)
            ->assertOk()
            ->assertJson([
                'id' => 1,
                'post_id' => 1,
                'tag_id' => $tag_model_mock->id,
            ]);
    }

    public function testAddTagIfTagExists()
    {
        $tag_model_mock = $this->getMockBuilder(Tag::class)
            ->onlyMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        $tag_model_mock->id = 2;

        $data = [
            'tag' => $tag_model_mock->id,
            'post_id' => 1
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['where', 'first'])
            ->addMethods(['select'])
            ->disableOriginalConstructor()
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('id')
            ->willReturnSelf();

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('name', $data['tag'])
            ->willReturnSelf();

        $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn($tag_model_mock);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Tag::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $link_model_mock = $this->getMockBuilder(PostTag::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $link_model_mock->expects($this->once())
            ->method('fill')
            ->with(['tag_id' => $tag_model_mock->id, 'post_id' => 1]);

        $link_model_mock->expects($this->once())
            ->method('save')
            ->willReturn(true);

        $link_model_mock->tag_id = $tag_model_mock->id;
        $link_model_mock->post_id = 1;
        $link_model_mock->id = 1;

        $this->instance(PostTag::class, $link_model_mock);

        $request_mock = $this->getMockBuilder(AddTagToPost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->instance(AddTagToPost::class, $request_mock);

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(\route('tags.add-to-post'), $data)
            ->assertOk()
            ->assertJson([
                'id' => 1,
                'post_id' => 1,
                'tag_id' => $tag_model_mock->id,
            ]);
    }

    public function testAddTagLinkModelSaveFailed()
    {
        $tag_model_mock = $this->getMockBuilder(Tag::class)
            ->onlyMethods([])
            ->disableOriginalConstructor()
            ->getMock();

        $tag_model_mock->id = 2;

        $data = [
            'tag' => $tag_model_mock->id,
            'post_id' => 1
        ];

        $builder_mock = $this->getMockBuilder(Builder::class)
            ->onlyMethods(['where', 'first'])
            ->addMethods(['select'])
            ->disableOriginalConstructor()
            ->getMock();

        $builder_mock->expects($this->once())
            ->method('select')
            ->with('id')
            ->willReturnSelf();

        $builder_mock->expects($this->once())
            ->method('where')
            ->with('name', $data['tag'])
            ->willReturnSelf();

        $builder_mock->expects($this->once())
            ->method('first')
            ->willReturn($tag_model_mock);

        $query_helper_mock = $this->getMockBuilder(GetModelQueryBuilder::class)
            ->onlyMethods(['queryBuilder'])
            ->getMock();

        $query_helper_mock->expects($this->once())
            ->method('queryBuilder')
            ->with(Tag::class)
            ->willReturn($builder_mock);

        $this->app->instance(
            GetModelQueryBuilderInterface::class,
            $query_helper_mock
        );

        $link_model_mock = $this->getMockBuilder(PostTag::class)
            ->onlyMethods(['fill', 'save'])
            ->disableOriginalConstructor()
            ->getMock();

        $link_model_mock->expects($this->once())
            ->method('fill')
            ->with(['tag_id' => $tag_model_mock->id, 'post_id' => 1]);

        $link_model_mock->expects($this->once())
            ->method('save')
            ->willReturn(false);

        $link_model_mock->tag_id = $tag_model_mock->id;
        $link_model_mock->post_id = 1;
        $link_model_mock->id = 1;

        $this->instance(PostTag::class, $link_model_mock);

        $request_mock = $this->getMockBuilder(AddTagToPost::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['validated'])
            ->getMock();

        $request_mock->expects($this->once())
            ->method('validated')
            ->willReturn($data);

        $this->instance(AddTagToPost::class, $request_mock);

        $this->withoutMiddleware(AuthWithToken::class)
            ->post(\route('tags.add-to-post'), $data)
            ->assertStatus(500);
    }
}
