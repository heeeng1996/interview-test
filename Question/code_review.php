<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // $orders = Order::all();

        // $result = [];

        // foreach ($orders as $order) {
        //     $user = User::find($order->user_id);
        //     $result[] = [
        //         'id' => $order->id,
        //         'user_name' => $user->name,
        //         'total' => $order->total,
        //     ];
        // }

        // return response()->json($result);

        // comment: 
        // 1- The junior should retrieve the data with the model relations instead of
        // call retrieve all the order and then in the loop, get the user name and merge it
        // into the array
        // 2- It is much better to response the data with the API Resource instead of straight
        // return out the data, also it is much better to return the data with pagination instead
        // of whole bulck of data return out, it will cause the performance issue.

        $orders = Order::with('user')
            ->paginate($request->input('per_page', 10), $request->input('page', 1));

        return OrderResource::collection($orders)
            ->additional([
                'message' => 'Orders retrieved successfully.',
            ])->response()
            ->setStatusCode(Response::HTTP_OK);
    }

    // public function store(Request $request)
    public function store(OrderRequest $request)
    {
        // $order = new Order();
        // $order->user_id = $request->user_id;
        // $order->total = $request->total;
        // $order->status = $request->status;
        // $order->save();

        // \DB::select("SELECT * FROM audit_log WHERE order_id = ".$order->id);

        // return response()->json($order);

        // comment
        // 1- The request is much recommended to use the form request to do the validation
        // before allow the data pass into the function and insert into the database.
        // 2- Before insert into the database, get the validated data and insert into the
        // database
        // 3- For the audit log it should be INSERT statement instead of SELECT statement,
        // but it is much better make the audit log into modal and insert the data into
        // audit log table.

        $order = Order::create($request->validated());

        AuditLog::create([
            'order_id' => $order->id,
            'action_by' => $request->user()->id,
            'action' => 'create',
        ]);

        return (new OrderResource($order))
            ->additional([
                'message' => 'Order created successfully.',
            ])
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    // public function destroy($id)
    public function destroy(Order $order)
    {
        // $order = Order::find($id);
        // $order->delete();
        // return response()->json(['message' => 'Deleted']);

        // comment
        // 1- The parameter should use Route Model Binding instead of string with "id"
        // 2- Instead of using find "id", can use findOrFail if not using Route Model Binding,
        // else Route Model Binding will auto query out the order

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully.'], Response::HTTP_OK);
    }
}
