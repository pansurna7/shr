<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuItem;

class BuilderControler extends Controller
{
    public function index($id)
    {
        $menu = Menu::findOrFail($id);
        // return $menu;
        return view('backend.menus.builder',compact('menu'));
    }

    public function order(Request $request,$id)
    {
        // $menu = Menu::findOrFail($id);
        $menuItemOrder = json_decode($request->order);
        $this->orderMenu($menuItemOrder,null);
        // flash()->success('Menu order updated successfully');

    }

    private function orderMenu(array $menuItem, $parentId)
    {
        foreach ($menuItem as $index => $item) {
            $menuItem = MenuItem::findOrFail($item->id);
            $menuItem->update([
                'order' =>$index + 1,
                'parent_id' => $parentId
            ]);
            if(isset($item->children)){
                $this->orderMenu($item->children, $menuItem->id);
            }
        }
    }
    public function itemCreate($id)
    {
        $menu = Menu::findOrFail($id);
        $menuItem = $menu;
        return view('backend.menus.item.create',compact('menu','menuItem'));
    }
    public function itemStore(Request $request,$id)
    {
        try {
            $request->validate([
                'type'          => 'required|string',
                'divider_title' => 'nullable|string',
                'title'         => 'nullable|string',
                'url'           => 'nullable|string',
                'target'        => 'nullable|string',
                'icon_class'    => 'nullable|string'
            ]);
            $menu = Menu::findOrFail($id);
            $menu->menuItems()->create([
                'type' => $request->type,
                'title' => $request->title,
                'divider_title' => $request->divider_title,
                'url' => $request->url,
                'target' => $request->target,
                'icon_class' => $request->icon_class,
            ]);

            flash()->success('Menu Item Created Successfully');
            return redirect()->route('menus.builder.index', $menu->id);


        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }

    }

    public function itemEdit($id,$itemId)
    {
        $menu = Menu::findOrFail($id);
        $menuItem= MenuItem::where('menu_id',$menu->id)->findOrFail($itemId);
        return view('backend.menus.item.edit',compact('menu','menuItem'));
    }
    public function itemUpdate(Request $request,$id,$itemId)
    {
        // return $request;
        try {
            $request->validate([
                'type'          => 'required|string',
                'divider_title' => 'nullable|string',
                'title'         => 'nullable|string',
                'url'           => 'nullable|string',
                'target'        => 'nullable|string',
                'icon_class'    => 'nullable|string'
            ]);
            $menu = Menu::findOrFail($id);
            $menuItem= MenuItem::where('menu_id',$menu->id)
            ->findOrFail($itemId)
            ->update([
                'type' => $request->type,
                'title' => $request->title,
                'divider_title' => $request->divider_title,
                'url' => $request->url,
                'target' => $request->target,
                'icon_class' => $request->icon_class,
            ]);

            flash()->success('Menu Item Updated Successfully');
            return redirect()->route('menus.builder.index', $menu->id);


        } catch (\Illuminate\Validation\ValidationException $e) {
            flash()->error('Please fix the errors in the form');
            return back()->withErrors($e->validator)->withInput();
        }

    }

    public function itemDelete($id, $itemId)
    {
        try {
            $menu = Menu::findOrFail($id)
                    ->menuItems()
                    ->findOrFail($itemId);

            $menu->delete();

            flash()->success('Menu Item Delete Successfully');
            return redirect()->back();

        } catch (\Throwable $e) {
            flash()->error('Please fix the errors in the form');
             return back()->withInput();
        }

    }

}
