<?php

namespace App\Orchid\Layouts;

use App\Models\Student;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class StudentListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'students';
    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('name', 'Name')
                ->render(function (Student $student) {
                    return $student->name;
                }),
            TD::make('email', 'Email')
                ->render(function (Student $student) {
                    return $student->email;
                }),
            TD::make('phone', 'Phone')
                ->render(function (Student $student) {
                    return $student->phone;
                }),
            TD::make('address', 'Address')
                ->render(function (Student $student) {
                    return $student->address;
                }),
            TD::make('city', 'City')
                ->render(function (Student $student) {
                    return $student->city;
                }),
            TD::make('state', 'State')
                ->render(function (Student $student) {
                    return $student->state;
                }),
            TD::make('country', 'Country')
                ->render(function (Student $student) {
                    return $student->country;
                }),
            TD::make('status', 'Status')
                ->render(function (Student $student) {
                    return Button::make($student->status==1 ? "Active" : "Inactive")
                        ->method('updateStudentStatus')
                        ->parameters([
                            'id' => $student->id,
                            'status' => $student->status==1 ? 0 : 1
                        ]);
                }),
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Student $student) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.student.edit', $student->id)
                                ->icon('pencil'),
                            
                            Link::make(__('Show Results'))
                                ->route('platform.studentrecord.list', $student->id)
                                ->icon('eye'),

                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->method('deleteStudent')
                                ->confirm(__('Once the student is deleted, all of its resources and data will be permanently deleted.'))
                                ->parameters([
                                    'id' => $student->id,
                                ]),
                        ]);
                }),
        ];
    }
}
