<?php
namespace App\Orchid\Screens;

use App\Orchid\Layouts\SubjectListLayout;
use App\Models\Subject;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class SubjectListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */

     public $name = "Subjects";
    public function query(): array
    {
        return [
            'subjects' => Subject::latest()->paginate(10)
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Link::make('Add')
                ->icon('plus')
                ->route('platform.subject.edit')
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            SubjectListLayout::class
        ];
    }
    public function deleteSubject(Subject $subject,Request $request)
    {
        $subject->studentrecords()->delete();
        $subject->delete();

        Alert::info('You have successfully deleted the subject.');

        return redirect()->route('platform.subject.list');
    }
}