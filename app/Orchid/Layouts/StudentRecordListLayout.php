<?php

namespace App\Orchid\Layouts;

use App\Models\StudentRecord;
use App\Models\Student;
use App\Models\Subject;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;

class StudentRecordListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'student_records';
    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            TD::make('name', 'Student Name')
                ->render(function (StudentRecord $studentrecord) {
                    return Student::where('id', $studentrecord->student_id)->first()->name;
                }),
            TD::make('email', 'Student Email')
                ->render(function (StudentRecord $studentrecord) {
                    return Student::where('id', $studentrecord->student_id)->first()->email;
                }),
            TD::make('phone', 'Student Phone')
                ->render(function (StudentRecord $studentrecord) {
                    return Student::where('id', $studentrecord->student_id)->first()->phone;
                }),
            TD::make('address', 'Student Address')
                ->render(function (StudentRecord $studentrecord) {
                    return Student::where('id', $studentrecord->student_id)->first()->address;
                }),
            TD::make('city', 'Student City')
                ->render(function (StudentRecord $studentrecord) {
                    return Student::where('id', $studentrecord->student_id)->first()->city;
                }),
            TD::make('state', 'Student State')
                ->render(function (StudentRecord $studentrecord) {
                    return Student::where('id', $studentrecord->student_id)->first()->state;
                }),
            TD::make('country', 'Student Country')
                ->render(function (StudentRecord $studentrecord) {
                    return Student::where('id', $studentrecord->student_id)->first()->country;
                }),                
            TD::make('status', 'Student Status')
            ->render(function (StudentRecord $studentrecord) {
                $student = Student::where('id', $studentrecord->student_id)->first();
                return ($student->status==1 ? "Active" : "Inactive");
            }),
            TD::make('subject', 'Subject')
                ->render(function (StudentRecord $studentrecord) {
                    return Subject::where('id', $studentrecord->subject_id)->first()->name;
                }),
            TD::make('marks_scored', 'Marks')
                ->render(function (StudentRecord $studentrecord) {
                    return $studentrecord->marks_scored;
                }),
            TD::make('grade', 'Grade')
                ->render(function (StudentRecord $studentrecord) {
                    return $studentrecord->grade;
                }),
            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (StudentRecord $studentrecord) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Link::make(__('Edit'))
                                ->route('platform.studentrecord.edit', $studentrecord->id)
                                ->icon('pencil'),
                            Button::make(__('Delete'))
                                ->icon('trash')
                                ->method('deleteStudentRecord')
                                ->confirm(__('Once the studentrecord is deleted, all of its resources and data will be permanently deleted.'))
                                ->parameters([
                                    'id' => $studentrecord->id,
                                ]),
                        ]);
                }),
        ];
    }
}
