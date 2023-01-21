@extends('layouts.sidebar')

@section('content')
<div class="post_create_container d-flex">
  <div class="post_create_area border w-50 m-5 p-5">
    <div class="">
      <p class="mb-0">カテゴリー</p>
      <select class="w-100" form="postCreate" name="post_category_id">
        @foreach($main_categories as $main_category)
        <optgroup label="{{ $main_category->main_category }}"></optgroup>
        <!-- サブカテゴリー表示 -->
        @foreach($main_category->subCategories as $sub_category)
        <option value="{{ $sub_category->id }}">{{ $sub_category->sub_category }}</option>
        @endforeach
        </optgroup>
        @endforeach
      </select>
    </div>
    <div class="mt-3">
      <p class="mb-0">タイトル</p>
      <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
    </div>
    @if ($errors->has('post_title'))
    <li>{{$errors->first('post_title')}}</li>
    @endif
    <div class="mt-3">
      <p class="mb-0">投稿内容</p>
      <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
    </div>
    @if ($errors->has('post_body'))
    <p>{{$errors->first('post_body')}}</p>
    @endif
    <div class="mt-3 text-right">
      <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
    </div>
    <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
  </div>
  @can('admin')
  <div class="w-25 ml-auto mr-auto">
    <div class="category_area mt-5 p-5">

      <div class="">
        <p class="m-0">メインカテゴリー</p>
        <form action="{{route('main.category.create')}}" method="POST">@csrf
          <input type="text" class="w-100" name="main_category_name" form="mainCategoryRequest">
          <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
        </form>
      </div>

      <p class=" m-0">サブカテゴリー</p>
      <select type="text" class="w-100" name="main_category_name" form="SubCategoryRequest">
        <option value="">---</option>
        @foreach($main_categories as $main_category)
        <option value="{{$main_category->id }}">{{$main_category->main_category }}</option>
        @endforeach
      </select>
      <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="CategoryRequest">
      <!-- サブカテゴリー追加 -->
      <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}
    </div>
    </form>
  </div>
</div>
@endcan
</div>
@endsection
