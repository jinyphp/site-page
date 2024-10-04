<?php
namespace Jiny\Site\Page\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithFileUploads;
use Livewire\Attributes\On;

class SiteAdminTemplate extends Component
{
    use WithFileUploads;
    use \Jiny\WireTable\Http\Trait\UploadSlot;

    public $filename;

    public $menus=[]; // 메뉴트리
    public $upload_path;

    public $viewFile;
    public $rows = [];

    public $popupForm = false;
    public $viewForm;
    public $viewList;

    public $popupDelete = false;
    public $confirm = false;

    public $actions = [];
    public $forms = [];
    public $edit_id;

    public $popupWindowWidth = "4xl";
    public $message;

    public function mount()
    {
        ## 화면처리
        // json 출력 트리
        if(!$this->viewFile) {
            $this->viewFile = 'jiny-site-page::admin.template.json';
        }

        $this->viewListFile();
        $this->viewFormFile();

        ## 테이터 읽기
        if(!$this->filename) {
            $this->filename = "template.json";
        }
        $this->menuload();

        // 데이터 파일명과 동일한 구조의 url 경로로 임시설정
        // $this->upload_path = DIRECTORY_SEPARATOR;
        // $code = str_replace('.json',"",$this->filename);
        // $this->upload_path .= $code; //str_replace(".", DIRECTORY_SEPARATOR, $code);

        $this->upload_path = "/"; // 1차 기본업로드 경로
        $this->upload_move = "/images/template/"; // 2차 이동할 경로(슬롯내부)

    }

    public function render()
    {
        // 기본값
        return view($this->viewFile);
    }



    protected function menuload($type="json")
    {
        $path = resource_path('templates');
        if(!is_dir($path)) mkdir($path,0777,true);

        if(file_exists($path.DIRECTORY_SEPARATOR.$this->filename)) {
            // $body = file_get_contents($path.DIRECTORY_SEPARATOR.$this->filename);
            // $menus = json_decode($body,true);
            $menus = json_file_decode($path.DIRECTORY_SEPARATOR.$this->filename);
        } else {
            $menus = [];
        }

        if($menus) {
            $this->rows = $menus;
        } else {
            $this->rows['items'] = [];
        }

    }

    protected function menusSave($rows, $filepath)
    {
        //$str = json_encode($rows, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );

        $path = resource_path('templates');
        if(!is_dir($path)) mkdir($path,0777,true);

        // //$filepath = str_replace(["/","."],DIRECTORY_SEPARATOR,$filepath);
        // file_put_contents($path.DIRECTORY_SEPARATOR.$filepath, $str);

        json_file_encode($path.DIRECTORY_SEPARATOR.$filepath, $rows);

        return true;
    }

    protected function viewListFile()
    {
        if(!$this->viewList) {
            //$this->viewList = 'jiny-menuss::list.group.list';
        }
    }

    protected function viewFormFile()
    {
        if(!$this->viewForm) {
            $this->viewForm = "jiny-site-page::admin.template.form";
            $this->actions['view']['form'] = $this->viewForm;
        }
    }


    protected $listeners = [
        'create','popupFormCreate',
        'edit','popupEdit','popupCreate',
        'setMenuFile'
    ];

    public $ref;

    public function create($ref=null)
    {
        if($ref) {
            $this->ref = trim($ref,'-');
        } else {
            $this->ref = null;
        }

        $this->popupForm = true;
        // 데이터초기화
        $this->forms = [];
    }

    public function store()
    {
        if($this->ref == null ) {
            $this->storeRoot();
        } else {
            $this->storeSub();
        }
    }

    private function storeRoot()
    {
        if(!empty($this->forms) && isset($this->forms['title'])) {

            // 파일업로드
            $this->fileUpload($this->forms, $this->upload_path);

            $this->rows['items'] []= $this->forms;
            $this->menusSave($this->rows, $this->filename);
        }

        $this->popupForm = false;
        $this->forms = [];
    }

    private function storeSub()
    {
        if(!empty($this->forms) && isset($this->forms['title'])) {
            $ref = explode('-',$this->ref);
            $temp = &$this->rows['items'];
            foreach( $ref  as $i) {
                if(isset($temp[$i])) {
                    $temp = &$temp[$i];
                }
                else if(isset($temp['items'][$i])) {
                    $temp = &$temp['items'][$i];
                }
            }

            // 파일업로드
            $this->fileUpload($this->forms, $this->upload_path);

            $temp['items'] []= $this->forms;
            $this->menusSave($this->rows, $this->filename);
        }

        $this->popupForm = false;
        $this->forms = [];
    }




    public function edit($id)
    {
        $this->actions['id'] = $id;
        $this->edit_id = $id;

        $ref = explode('-',$id);
        $temp = &$this->rows['items'];
        foreach( $ref  as $i) {
            if(isset($temp[$i])) {
                $temp = &$temp[$i];
            }
            else if(isset($temp['items'][$i])) {
                $temp = &$temp['items'][$i];
            }
        }

        $this->forms = $temp;
        $this->popupForm = true;
    }


    public function update()
    {
        // 2. 시간정보 생성
        $this->forms['updated_at'] = date("Y-m-d H:i:s");

        // 3. 파일 업로드 체크 Trait
        // $this->upload_path = "/"; // 1차 기본업로드 경로
        // $this->upload_move = $this->uri; // 2차 이동할 경로(슬롯)"/images/widgets/".$this->uri; // 슬롯 안쪽으로 이동
        $this->fileUpload($this->forms, $this->upload_path);

        $id = $this->edit_id;

        $ref = explode('-',$id);
        $temp = &$this->rows['items'];
        foreach( $ref  as $i) {
            if(isset($temp[$i])) {
                $temp = &$temp[$i];
            }
            else if(isset($temp['items'][$i])) {
                $temp = &$temp['items'][$i];
            }
        }

        foreach($this->forms as $key => $value) {
            $temp[$key] = $value;
        }

        $this->menusSave($this->rows, $this->filename);

        $this->forms = [];
        $this->edit_id = null;
        $this->actions['id'] = null;
        $this->popupForm = false;

    }


    public function cancel()
    {
        $this->forms = [];
        $this->edit_id = null;
        $this->popupForm = false;
        $this->setup = false;
    }


    /**
     * 삭제 팝업창 활성화
     */
    public function delete($id=null)
    {
        $this->popupDelete = true;
    }


    public function deleteCancel()
    {
        $this->popupDelete = false;
        $this->popupForm = false;
        $this->setup = false;
    }

    /**
     * 삭제 확인 컨펌을 하는 경우에,
     * 실제적인 삭제가 이루어짐
     */
    public function deleteConfirm()
    {
        $this->popupDelete = false;
        $this->popupForm = false;
        $this->setup = false;

        $id = $this->edit_id;
        $this->edit_id = null;

        // 이미지삭제
        // $this->deleteUploadFiles($this->rows[$id]);


        // 데이터삭제
        $ref = explode('-',trim($id,'-'));
        //dump($ref);
        $temp = &$this->rows['items'];
        $ddd = &$this->rows['items'];
        foreach( $ref as $i) {
            if(isset($temp[$i])) {
                $ddd = &$temp;
                $temp = &$temp[$i];

            }
            else if(isset($temp['items'][$i])) {
                $ddd = &$temp['items'];
                $temp = &$temp['items'][$i];

            }
        }

        unset($ddd[$i]);

        $this->menusSave($this->rows, $this->filename);
    }

    // 삭제해야 되는 이미지가 있는 경우
    protected function deleteUploadFiles($form)
    {
        $path = storage_path('app');
        $type_name = ["image", "img", "images", "upload"];

        foreach($form as $key => $item) {
            if(in_array($key, $type_name)) {
                $filepath = $path."/".$item;
                if(file_exists($filepath)) {
                    unlink($filepath);
                }
            }
        }
    }


    /**
     * 이벤트
     */
    public function setMenuFile($value)
    {
        //dd($value);
        $this->filename = str_replace(".json","",$value);
        $this->menuload();
    }


    public function itemUp($item)
    {

    }

    public function itemDown($item)
    {

    }



}
