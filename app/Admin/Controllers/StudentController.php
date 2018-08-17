<?php

namespace App\Admin\Controllers;

use App\Student;
use App\Classroom;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\MessageBag;

class StudentController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('Index');
            $content->description('description');

            $content->body($this->grid());
        });
    }

    /**
     * Show interface.
     *
     * @param $id
     * @return Content
     */
    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Detail');
            $content->description('description');

            $content->body(Admin::show(Student::findOrFail($id), function (Show $show) {

                $show->id();

                $show->created_at();
                $show->updated_at();
            }));
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('Edit');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('Create');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Student::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
			$grid->name('Name')->badge('warning');
			$grid->describe();
			$grid->email('Email');
            $grid->created_at();
            $grid->updated_at();
			$grid->orderable();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Student::class, function (Form $form) {

            $form->display('id', 'ID');
			$form->text('Name');
			$form->email('Email');
			$form->text('classroom.class_name');
			$form->text('classroom.amount');
			$status = [
				0 => 'Active',
				1 => 'InActive',
			];
			$form->select('status', 'Status')->options($status);
            $form->display('updated_at', 'Updated At');
			$form->saving(function ($form) {
				$obj = new Classroom;
				$obj->class_name = $form->classroom['class_name'];
				$obj->amount = $form->classroom['amount'];
				$obj->save();
			});
        });
    }
}
