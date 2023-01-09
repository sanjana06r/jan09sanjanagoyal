<?php

namespace App\Orchid\Screens;

use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class SubjectEditScreen extends Screen
{
    /**
     * @var Subject
     */
    public $exists = false;
    public $name = '';

    /**
     * Query data.
     *
     * @param Subject $subject
     *
     * @return array
     */
    public function query(Subject $subject): array
    {
        $this->exists = $subject->exists;
        $this->name = $this->name();
        return [
            'subject' => $subject
        ];
    }

    /**
     * The name is displayed on the user's screen and in the headers
     */
    public function name(): ?string
    {
        return $this->exists ? 'Edit subject' : 'Creating a new subject';
    }

    /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Subjects";
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Create New')
                ->icon('plus')
                ->method('createOrUpdate')
                ->canSee(!$this->exists),

            Button::make('Update')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->exists),

            Button::make('Remove')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->exists),
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
            Layout::rows([
                Input::make('subject.name')
                    ->type('text')
                    ->max(255)
                    ->title(__('Name'))
                    ->placeholder(__('Enter Subject')),

            ])
        ];
    }

    /**
     * @param Subject    $subject
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Subject $subject, Request $request)
    {
        $data = $request->all();
        $validation = Validator::make(
            $request->all(),
            [
                'subject.*' => 'required',
                'subject.name' => [
                    'required', 'max:255',
                    function ($attribute, $value, $fail) use ($data,$subject){
                        $exists = Subject::where('name', $data['subject']['name'])->count();
                        if($subject['id']){
                            if($subject['name'] != $data['subject']['name'] && $exists) {
                                $fail('Subject name already taken');
                            }
                        }elseif($exists){
                            $fail('Subject name already taken');
                        }
                    },
                ],
            ],
        );
        $subject->fill($request->get('subject'))->save();

        Alert::info('You have successfully created a subject.');

        return redirect()->route('platform.subject.list');
    }

    /**
     * @param Subject $subject
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(Subject $subject)
    {
        $subject->delete();

        Alert::info('You have successfully deleted the subject.');

        return redirect()->route('platform.subject.list');
    }
}
