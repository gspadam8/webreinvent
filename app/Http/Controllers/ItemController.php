<?php
namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return view('items.index');
    }

    public function fetchItems()
    {
        $items = Item::all();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required | unique:items',
        ], [
            'name.unique' => 'This Task has been created!, please check your list.'
        ]);

        $item = Item::create(['name' => $request->name]);
        return response()->json($item);
    }

    public function updateStatus($id)
    {
        $item = Item::findOrFail($id);
        $item->status = 1; // Mark as done
        $item->save();

        return response()->json(['status' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $item->name = $request->name;
        $item->save();

        return response()->json(['status' => 'Item updated successfully']);
    }

    public function destroy($id)
    {
        Item::destroy($id);
        return response()->json(['status' => 'Item deleted successfully']);
    }

    public function fetchSelectedItems()
    {
        $items = Item::where('status', 0)->get();
        return response()->json($items);
    }
}
