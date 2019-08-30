package com.dinosoftlabs.foodies.android.HActivitiesAndFragment;

import android.app.Dialog;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.percent.PercentRelativeLayout;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.firebase.ui.database.FirebaseRecyclerAdapter;
import com.firebase.ui.database.FirebaseRecyclerOptions;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.Query;
import com.google.firebase.database.ValueEventListener;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.OrderDetailFragment;
import com.dinosoftlabs.foodies.android.Adapters.OrderAdapter;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HModels.NewOrderModelClass;
import com.dinosoftlabs.foodies.android.Models.OrderModelClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

import java.util.ArrayList;
import java.util.HashMap;

/**
 * Created by Nabeel on 2/14/2018.
 */

public class HJobsFragment extends Fragment {
    ImageView filter_search;
    public static boolean ACCEPTED_ORDER,FLAG_HJOBS,PENDING_ORDER,CURRENT_ORDER;

    SharedPreferences sPre;
    RelativeLayout order_type_div;

    RecyclerView order_list;

    LinearLayoutManager  recyclerViewlayoutManager;
    OrderAdapter recyclerViewadapter;

    LinearLayout recycler_view_restaurant;
    CamomileSpinner orderProgressBar;
    RelativeLayout progressDialog;
    SwipeRefreshLayout refresh_layout;
    String PendingOrders = "PendingOrders";
    String AcceptedOrders = "AcceptedOrders";

    ArrayList<OrderModelClass> orderArrayList;
    TextView order_tv;
    @SuppressWarnings("deprecation")
    PercentRelativeLayout no_job_div;

    DatabaseReference mDatabase;
    FirebaseDatabase firebaseDatabase;
    String user_id;

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_STATE_VISIBLE|WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE);
        View view = inflater.inflate(R.layout.orders_fragment, container, false);
        FrameLayout frameLayout = view.findViewById(R.id.order_fragment_container);
        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);
        frameLayout.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });

        initUI(view);
        sPre = getContext().getSharedPreferences(PreferenceClass.user,getContext().MODE_PRIVATE);
        user_id = sPre.getString(PreferenceClass.pre_user_id,"");

        order_list = view.findViewById(R.id.order_history_recyclerview);
        orderProgressBar = view.findViewById(R.id.orderProgress);
        orderProgressBar.start();
        progressDialog =view.findViewById(R.id.progressDialog);
        order_list.setHasFixedSize(false);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
       // recyclerViewlayoutManager.setStackFromEnd(true);
        recyclerViewlayoutManager.setReverseLayout(true);
        recyclerViewlayoutManager.setStackFromEnd(true);
        order_list.setLayoutManager(recyclerViewlayoutManager);
        order_tv.setText("Current Order");
        getAllOrderParser();

        return view;
    }


    private void initUI(View v){
        order_tv = v.findViewById(R.id.order_tv);

        order_type_div = v.findViewById(R.id.order_type_div);
        order_type_div.setVisibility(View.VISIBLE);
        no_job_div = v.findViewById(R.id.no_job_div);
        refresh_layout = v.findViewById(R.id.refresh_layout);
        refresh_layout.setRefreshing(false);
        refresh_layout.setEnabled(false);


        recycler_view_restaurant = v.findViewById(R.id.recycler_view_restaurant );
        filter_search = v.findViewById(R.id.filter_search);
        filter_search.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                customDialogbox();
            }
        });




    }

    @Override
    public void onAttachFragment(Fragment childFragment) {
        super.onAttachFragment(childFragment);
        setUserVisibleHint(false);
    }

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setUserVisibleHint(false);
    }

    @Override
    public void setMenuVisibility(boolean menuVisible) {
        super.setMenuVisibility(menuVisible);


    }

    private void getAllOrderParser(){

        progressDialog.setVisibility(View.VISIBLE);
        firebaseDatabase = FirebaseDatabase.getInstance();
        mDatabase = firebaseDatabase.getReference().child("restaurant").child(user_id).child("CurrentOrders");
        Query query=mDatabase.orderByKey();
        FirebaseRecyclerOptions<NewOrderModelClass> options =
                new FirebaseRecyclerOptions.Builder<NewOrderModelClass>()
                        .setQuery(query, NewOrderModelClass.class)
                        .build();


        final FirebaseRecyclerAdapter<NewOrderModelClass,Orderviewholder> fRadapter =
                new FirebaseRecyclerAdapter<NewOrderModelClass, Orderviewholder>(options)
                {
                    @Override
                    protected void onBindViewHolder(Orderviewholder holder, int position, final NewOrderModelClass model) {
                        holder.order_id.setText("Order # "+model.getOrder_id());
                        if(model.getStatus().equals("0")){
                            holder.deal_image.setVisibility(View.GONE);
                            holder.status_text.setVisibility(View.VISIBLE);
                            holder.status_text.setText("New");
                        }else {
                            if(model.getDeal().equals("1")){
                                holder.deal_image.setVisibility(View.VISIBLE);
                                holder.status_text.setVisibility(View.GONE);
                                holder.deal_image.setImageDrawable(getContext().getResources().getDrawable(R.drawable.deal_img));
                            }
                            else {
                                holder.status_text.setVisibility(View.GONE);
                            }
                        }
                        holder.main_layout.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {
                                ChangeStatus(model.getOrder_id());
                                SharedPreferences.Editor editor = sPre.edit();
                                editor.putString(PreferenceClass.ORDER_ID,model.getOrder_id());
                                editor.putBoolean("Current_order",true);
                                editor.commit();

                                Fragment restaurantMenuItemsFragment = new OrderDetailFragment();
                                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                transaction.add(R.id.order_fragment_container, restaurantMenuItemsFragment, "ParentFragment").commit();

                                FLAG_HJOBS = true;
                                CURRENT_ORDER = true;
                                // progressDialog.setVisibility(View.GONE);
                             //   initViewMenuOrderExtraItem(view,false);

                            }
                        });
                    }

                    @Override
                    public Orderviewholder onCreateViewHolder(ViewGroup parent, int viewType) {
                        View view = LayoutInflater.from(parent.getContext())
                                .inflate(R.layout.row_item_orders,parent,false);

                        return new Orderviewholder(view);
                    }


                    @Override
                    public void onAttachedToRecyclerView(RecyclerView recyclerView) {
                        super.onAttachedToRecyclerView(recyclerView);
                        progressDialog.setVisibility(View.GONE);
                      //  view.findViewById(R.id.orderProgress).setVisibility(View.GONE);

                    }
                };

        fRadapter.registerAdapterDataObserver(new RecyclerView.AdapterDataObserver() {
            @Override
            public void onItemRangeInserted(int positionStart, int itemCount) {
                fRadapter.notifyDataSetChanged();

            }
            @Override
            public void onItemRangeChanged(int positionStart, int itemCount, Object payload) {
                super.onItemRangeChanged(positionStart, itemCount, payload);
                fRadapter.notifyDataSetChanged();
            }
        });

        fRadapter.startListening();

        order_list.setAdapter(fRadapter);
        fRadapter.notifyDataSetChanged();




     /*   orderProgressBar.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        orderArrayList = new ArrayList<>();
        String user_id = sPre.getString(PreferenceClass.pre_user_id,"");
        RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();
        try {

            jsonObject.put("user_id",user_id);
           // jsonObject.put("status",status_);
            //jsonObject.put("lat", latitude);
            //jsonObject.put("long", longitude);

            Log.e("Obj",jsonObject.toString());

        } catch (JSONException e) {
            e.printStackTrace();
        }
        // Request a string response from the provided URL.
        JsonObjectRequest jsonObjReq = new JsonObjectRequest(Request.Method.POST,
                Config.SHOW_ORDER_BASED_RESTAU,jsonObject,
                new Response.Listener<JSONObject>() {

                    @Override
                    public void onResponse(JSONObject response) {

                        Log.d("JSONPost", response.toString());
                        String strJson = response.toString();
                        JSONObject jsonResponse = null;
                        try {
                            jsonResponse = new JSONObject(strJson);

                            Log.d("JSONPost", jsonResponse.toString());

                            int code_id = Integer.parseInt(jsonResponse.optString("code"));

                            if (code_id == 200) {

                                JSONObject json = new JSONObject(jsonResponse.toString());
                                JSONArray jsonarray = json.getJSONArray(orderType);

                                Log.d("Length", String.valueOf(jsonarray.length()));

                                if (jsonarray.length() == 0) {
                                    recycler_view_restaurant.setVisibility(View.GONE);

                                } else {
                                    recycler_view_restaurant.setVisibility(View.VISIBLE);
                                }

                                for (int i = 0; i < jsonarray.length(); i++) {

                                    JSONObject json1 = jsonarray.getJSONObject(i);
                                    OrderModelClass orderModelClass = new OrderModelClass();
                                    JSONObject jsonObjOrder = json1.getJSONObject("Order");
                                    JSONObject jsonObjCurrency = jsonObjOrder.getJSONObject("Currency");

                                    if (jsonObjOrder.getJSONArray("OrderMenuItem") != null) {
                                        JSONArray jsonarrayOrder = jsonObjOrder.getJSONArray("OrderMenuItem");

                                        JSONObject jsonObjectMenu = jsonarrayOrder.getJSONObject(0);

                                        JSONArray jsonarrayExtraOrder = jsonObjectMenu.getJSONArray("OrderMenuExtraItem");


                                        if (jsonarrayExtraOrder.length() != 0) {
                                            JSONObject jsonObjectExtraMenu = jsonarrayExtraOrder.getJSONObject(0);
                                            orderModelClass.setOrder_extra_item_name(jsonObjectExtraMenu.optString("name"));
                                        }

                                        orderModelClass.setCurrency_symbol(jsonObjCurrency.optString("symbol"));
                                        orderModelClass.setOrder_created(jsonObjOrder.optString("created"));

                                        orderModelClass.setOrder_id(jsonObjOrder.optString("id"));
                                        orderModelClass.setOrder_menu_id(jsonObjectMenu.optString("id"));
                                        orderModelClass.setOrder_name(jsonObjectMenu.optString("name"));
                                        orderModelClass.setOrder_price(jsonObjOrder.optString("price"));
                                        orderModelClass.setInstructions(jsonObjOrder.optString("instructions"));
                                        orderModelClass.setRestaurant_name(jsonObjOrder.optString("name"));
                                        orderModelClass.setOrder_quantity(jsonObjOrder.optString("quantity"));
                                    }

                                    orderArrayList.add(orderModelClass);
                                    // Toast.makeText(getContext(),orderModelClassArrayList.toString(),Toast.LENGTH_SHORT).show();

                                }

                                if (orderArrayList != null) {

                                    if (orderArrayList.size() > 0) {
                                        no_job_div.setVisibility(View.GONE);
                                    } else if (orderArrayList.size() == 0) {
                                        no_job_div.setVisibility(View.VISIBLE);
                                    }
                                    progressDialog.setVisibility(View.GONE);
                                    orderProgressBar.setVisibility(View.GONE);
                                    recyclerViewadapter = new OrderAdapter(orderArrayList, getActivity());
                                    order_history_recyclerview.setAdapter(recyclerViewadapter);
                                    recyclerViewadapter.notifyDataSetChanged();

                                }
                                recyclerViewadapter.setOnItemClickListner(new OrderAdapter.OnItemClickListner() {
                                    @Override
                                    public void OnItemClicked(View view, int position) {

                                        SharedPreferences.Editor editor = sPre.edit();
                                        editor.putString(PreferenceClass.ORDER_HEADER, orderArrayList.get(position).getOrder_name());
                                        editor.putString(PreferenceClass.ORDER_ID, orderArrayList.get(position).getOrder_id());
                                        editor.putString(PreferenceClass.ORDER_INS, orderArrayList.get(position).getInstructions());
                                        editor.commit();
                                        Fragment restaurantMenuItemsFragment = new OrderDetailFragment();
                                        FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                        transaction.add(R.id.order_fragment_container, restaurantMenuItemsFragment, "ParentFragment").commit();

                                        FLAG_HJOBS = true;

                                    }
                                });

                            }

                            //JSONArray jsonMainNode = jsonResponse.optJSONArray("msg");                            }
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }

                        //pDialog.hide();
                    }
                }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                //  ed_progress.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                progressDialog.setVisibility(View.GONE);
              //  Toast.makeText(getContext(), error.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });

// Add the request to the RequestQueue.
        queue.add(jsonObjReq);*/

    }


    private void getAllPendingOrderParser() {
        progressDialog.setVisibility(View.VISIBLE);
        Query query=FirebaseDatabase.getInstance().getReference().child("restaurant").child(user_id).child("PendingOrders");
        FirebaseRecyclerOptions<NewOrderModelClass> options2 =
                new FirebaseRecyclerOptions.Builder<NewOrderModelClass>()
                        .setQuery(query, NewOrderModelClass.class)
                        .build();


        FirebaseRecyclerAdapter<NewOrderModelClass,Orderviewholder> pendingadapter =
                new FirebaseRecyclerAdapter<NewOrderModelClass, Orderviewholder>(options2)
                {
                    @Override
                    protected void onBindViewHolder(Orderviewholder holder, int position, final NewOrderModelClass model) {
                        holder.order_id.setText("Order # "+model.getOrder_id());
                        if(model.getStatus().equals("0")){
                            holder.deal_image.setVisibility(View.GONE);
                            holder.status_text.setVisibility(View.GONE);
                        }else {
                            if(model.getDeal().equals("1")){
                                holder.deal_image.setVisibility(View.VISIBLE);
                                holder.status_text.setVisibility(View.GONE);
                                holder.deal_image.setImageDrawable(getContext().getResources().getDrawable(R.drawable.deal_img));
                            }
                        }
                        holder.main_layout.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {

                                SharedPreferences.Editor editor = sPre.edit();
                                editor.putString(PreferenceClass.ORDER_ID,model.getOrder_id());
                                editor.putBoolean("Current_order",false);
                                editor.commit();
                                Fragment restaurantMenuItemsFragment = new OrderDetailFragment();
                                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                transaction.add(R.id.order_fragment_container, restaurantMenuItemsFragment, "ParentFragment").commit();
                                // initViewMenuOrderExtraItem(view,false);
                                FLAG_HJOBS = true;
                                PENDING_ORDER = true;
                            }
                        });

                    }
                    @Override
                    public Orderviewholder onCreateViewHolder(ViewGroup parent, int viewType) {
                        View view = LayoutInflater.from(parent.getContext())
                                .inflate(R.layout.row_item_orders,parent,false);
                        return new Orderviewholder(view);
                    }

                    @Override
                    public void onAttachedToRecyclerView(RecyclerView recyclerView) {
                        super.onAttachedToRecyclerView(recyclerView);
                        progressDialog.setVisibility(View.GONE);
                    }
                };
        pendingadapter.registerAdapterDataObserver(new RecyclerView.AdapterDataObserver() {
            @Override
            public void onItemRangeInserted(int positionStart, int itemCount) {
                order_list.smoothScrollToPosition(0);
            }

        });

        pendingadapter.startListening();

        order_list.setAdapter(pendingadapter);
        pendingadapter.notifyDataSetChanged();

    }



    private void ChangeStatus(String order_id) {
        final DatabaseReference mref=FirebaseDatabase.getInstance().getReference();

        final Query query2 =mref.child("restaurant").child(user_id).child("CurrentOrders").orderByChild("order_id").equalTo(order_id);
        query2.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                for (DataSnapshot nodeDataSnapshot : dataSnapshot.getChildren()) {
                    String key = nodeDataSnapshot.getKey(); // this key is `K1NRz9l5PU_0CFDtgXz`
                    String path = "restaurant"+ "/" +user_id+ "/" + "CurrentOrders" + "/" + key;
                    HashMap<String, Object> result = new HashMap<>();
                    result.put("status", "1");
                    mref.child(path).updateChildren(result);
                }

            }

            @Override
            public void onCancelled(DatabaseError databaseError) {

            }
        });


    }



    class Orderviewholder extends RecyclerView.ViewHolder{
        TextView order_id,status_text;
        ImageView deal_image;
        View view;
        RelativeLayout main_layout;
        public Orderviewholder(View itemView) {
            super(itemView);
            view=itemView;
            this.order_id=(TextView) view.findViewById(R.id.order_id);
            this.deal_image=view.findViewById(R.id.deal_image);
            this.status_text=(TextView) view.findViewById(R.id.text_view);
            this.main_layout=view.findViewById(R.id.main_layout);
        }

    }

    public void customDialogbox(){

        // custom dialog
        final Dialog dialog = new Dialog(getContext());
        dialog.setContentView(R.layout.custom_dialoge_box);
        dialog.setTitle("Order Filter");

        // set the custom dialog components - text, image and button

        RelativeLayout cancelDiv = (RelativeLayout) dialog.findViewById(R.id.forth);
        RelativeLayout currentOrderDiv = (RelativeLayout) dialog.findViewById(R.id.second);
        RelativeLayout pastOrderDiv = (RelativeLayout) dialog.findViewById(R.id.third);

        TextView textView1 = (TextView)dialog.findViewById(R.id.first_tv);
        textView1.setText("Current Orders");
        TextView textView2 = (TextView)dialog.findViewById(R.id.second_tv);
        textView2.setText("Pending Orders");

        currentOrderDiv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
             getAllOrderParser();
             order_tv.setText("Current Order");
                ACCEPTED_ORDER = false;
                dialog.dismiss();
            }
        });

        pastOrderDiv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
               getAllPendingOrderParser();
                ACCEPTED_ORDER = true;
                order_tv.setText("Pending Order");
                dialog.dismiss();
            }
        });

        // if button is clicked, close the custom dialog
        cancelDiv.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                dialog.dismiss();
            }
        });

        dialog.show();
    }


}

