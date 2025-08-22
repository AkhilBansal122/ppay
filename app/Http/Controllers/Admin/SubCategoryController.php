<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Str;
use Illuminate\Validation\Rule;

class SubCategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:Sub-Category-Management', ['only' => ['index', 'store', 'create', 'edit', 'destroy', 'update']]);
        $this->Model = new SubCategory;
        $this->uploadPath = 'uploads/admin/subCategory/';
        $this->columns = [
            "id",
            'category_id',
            "name",
            "slug",
            'image',
            'description',
            'status',
            'created_at'
        ];

    }

    public function index()
    {
        return view('admin.subCategories.index');

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('status',1)->get();
        return view('admin.subcategories.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:sub_categories,name',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ], [
            'name.required' => 'Name is required.',
            'name.unique' => 'This name already exists.',
            'image.required' => 'Please upload an image.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image size must not exceed 2MB.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
        ]);

        // Define the upload path
        $uploadPath = public_path($this->uploadPath);

        // Check if the directory exists, if not create it with permissions
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0775, true); // 0775 allows read/write/execute for owner and group, and read/execute for others
        }

        // Handle file upload
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($uploadPath, $imageName);
        }

        // Create new main category
        $this->Model::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'image' => $imageName,
            'status' => 1, // default to active
        ]);

        return redirect()->route('sub-categories.index')->with('success', 'Sub Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $categories = Category::where('status',1)->get();
        $subCategory = SubCategory::with('category')->where("id", $id)->first();
        $subCategory->image = "/" . $this->uploadPath . $subCategory->image;
        //$mainCategoryimage =$mainCategory->image ? config('custom.public_path'). '/'.$this->uploadPath.$mainCategory->image :'';
        return view('admin.subcategories.edit', compact(['subCategory', 'categories']));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     dd($request->all());
    // }
    public function update(Request $request, SubCategory $subCategory)
    {


        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_categories')->ignore($subCategory->id),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'description' => 'nullable|string',
            'category_id' => [
                'required',
                'string',
                'exists:categories,id',
            ],
        ], [
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'name.unique' => 'This name already exists.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The image size must not exceed 2MB.',
        ]);


        // Define the upload path
        $uploadPath = public_path($this->uploadPath);

        // Check if the directory exists, if not create it with permissions
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }
        $imageName = "";
        if ($request->hasFile('image')) {
            if ($subCategory->image) {
                $oldImagePath = $uploadPath . '/' . $subCategory->image;
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $imageName = time() . '.' . $request->image->extension();
            $request->image->move($uploadPath, $imageName);
        }
        else{
        $imageName = $subCategory->image;
        }

        $subCategory->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'image'=>$imageName,
            'description' => $request->description,
        ]);

        return redirect()->route('sub-categories.index')->with('success', 'Sub Category updated successfully.');
    }


    public function getData(Request $request)
    {
        $request->search = $request->search;
        if (isset($request->order[0]['column'])) {
            $request->order_column = $request->order[0]['column'];
            $request->order_dir = $request->order[0]['dir'];
        }

        $records = $this->Model->fetchData($request, $this->columns);
        $total = $records->get();
        if (isset($request->start)) {
            $subCategories = $records->offset($request->start)->limit($request->length)->get();
        } else {
            $subCategories = $records->offset($request->start)->limit(count($total))->get();
        }
        $result = [];


        $i = 1;
        foreach ($subCategories as $value) {
            $data = [];
            $data['srno'] = $i++;
            $data['id'] = $value->id;
            $data['name'] = ucfirst($value->name);
            $data['category_name'] = ucfirst($value->category->name ?? 'N/A');
            $data['status'] = isActiveInactive($value->status, route('sub-categories.statusChange'), $value->id);
            $data['image'] = "<img class='avatar-image avatar-md bg-warning text-white' src='" . config('custom.public_path') . '/' . $this->uploadPath . '/' . $value->image . "'/>";
            $data['description'] = $value->description;
            $data['created_at'] = dateFormat($value->created_at); // Assuming created_at is a Carbon instance
            $action = actions([
                'edit' => route('sub-categories.edit', $value->id),
                'view' => '',   // You may consider removing this if it's not used
                'delete' => ''  // You may consider removing this if it's not used
            ]);

            $data['actions'] = $action;
            $result[] = $data;
        }

        $data = json_encode([
            'data' => $result,
            'recordsTotal' => count($total),
            'recordsFiltered' => count($total),
        ]);
        return $data;
    }

    public function statusChange(Request $request)
    {
        return statusChange($request, $this->Model);
    }
}
