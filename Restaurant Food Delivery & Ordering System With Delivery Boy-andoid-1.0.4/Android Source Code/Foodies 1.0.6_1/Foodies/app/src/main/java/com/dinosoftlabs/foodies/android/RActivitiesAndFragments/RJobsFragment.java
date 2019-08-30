package com.dinosoftlabs.foodies.android.RActivitiesAndFragments;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.pm.PackageManager;
import android.location.Address;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.ActivityCompat;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.content.ContextCompat;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.DefaultRetryPolicy;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.firebase.ui.database.FirebaseRecyclerAdapter;
import com.firebase.ui.database.FirebaseRecyclerOptions;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.GooglePlayServicesUtil;
import com.google.android.gms.common.api.GoogleApiClient;

import com.google.android.gms.tasks.OnCompleteListener;
import com.google.android.gms.tasks.Task;
import com.google.firebase.database.DataSnapshot;
import com.google.firebase.database.DatabaseError;
import com.google.firebase.database.DatabaseReference;
import com.google.firebase.database.FirebaseDatabase;
import com.google.firebase.database.Query;
import com.google.firebase.database.ValueEventListener;

import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;

import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RAdapters.RCurrentJobAdapter;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RAdapters.RPendingJobAdapter;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderModels.ROrderModel;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderModels.RiderJobModel;

import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.Services.UpdateLocation;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Calendar;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;


/**
 * Created by Nabeel on 1/15/2018.
 */

public class RJobsFragment extends Fragment implements GoogleApiClient.OnConnectionFailedListener, GoogleApiClient.ConnectionCallbacks {

    SharedPreferences pending_job_pref;
    ArrayList<RiderJobModel> riderJobList;
    ArrayList<RiderJobModel> riderCurrentJobList;
    RPendingJobAdapter dataAdapter;
    RCurrentJobAdapter dataAdapter2;
    RecyclerView pending_job_rv;
    RecyclerView current_job_rv;

    RecyclerView.LayoutManager recyclerViewlayoutManager;
    RecyclerView.LayoutManager recyclerViewlayoutManager2;

    UpdateLocation gpsTracker;
    String user_id;

    private static final int REQUEST_PERMISSIONS = 100;
    boolean boolean_permission;

    Double latitude, longitude;
    public static boolean FLAG_CURRENT_JOB;
    TextView time_check_tv;
    Button r_check_out_btn;
    DatabaseReference mDatabase;
    FirebaseDatabase firebaseDatabase;

    List<Address> user = null;


    private final static int PLAY_SERVICES_RESOLUTION_REQUEST = 1000;

    CamomileSpinner customProgress;
    public RelativeLayout progressDialog;
    RelativeLayout transparent_layer;

    private GoogleApiClient mGoogleApiClient;
    private static String serverkey;
    private String KEY;

    @Override
    public void onCreate(@Nullable Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_STATE_ALWAYS_HIDDEN);
        getActivity().getWindow().setSoftInputMode(
                WindowManager.LayoutParams.SOFT_INPUT_STATE_VISIBLE | WindowManager.LayoutParams.SOFT_INPUT_ADJUST_RESIZE);
        View v = inflater.inflate(R.layout.rider_jobs_layout, container, false);
        pending_job_pref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        user_id = pending_job_pref.getString(PreferenceClass.pre_user_id, "");
        customProgress = v.findViewById(R.id.customProgress);
        progressDialog = v.findViewById(R.id.progressDialog);
        customProgress.start();
        transparent_layer = v.findViewById(R.id.transparent_layer);

        FrameLayout frameLayout = v.findViewById(R.id.main_container);
        FontHelper.applyFont(getContext(), frameLayout, AllConstants.verdana);
        frameLayout.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                return true;
            }
        });

        pending_job_rv = v.findViewById(R.id.pending_job_list);
        current_job_rv = v.findViewById(R.id.current_job_list);
        time_check_tv = v.findViewById(R.id.time_check_tv);

        pending_job_rv.setHasFixedSize(false);
        current_job_rv.setHasFixedSize(false);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        recyclerViewlayoutManager2 = new LinearLayoutManager(getContext());
        pending_job_rv.setLayoutManager(recyclerViewlayoutManager);
        current_job_rv.setLayoutManager(recyclerViewlayoutManager2);

        r_check_out_btn = v.findViewById(R.id.r_check_out_btn);

        // gpsTracker = new UpdateLocation();

        fn_permission();
        if (boolean_permission) {

            Intent intent = new Intent(getContext(), UpdateLocation.class);
            getContext().startService(intent);

        } else {
            Toast.makeText(getContext(), "Enable GPS", Toast.LENGTH_SHORT).show();
        }

        shouwOnlineStatus();

        showComingRiderShift();

        getPendingOrderList();
        getCurrentOrderList();


        r_check_out_btn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                getContext().startActivity(new Intent(getContext(), ROnlineStatusActivity.class));
                getActivity().finish();
            }
        });


        return v;
    }


    public void getCurrentOrderList(){
       /* progressDialog.setVisibility(View.VISIBLE);
        transparent_layer.setVisibility(View.VISIBLE);
        riderJobList = new ArrayList<>();
*/

        firebaseDatabase = FirebaseDatabase.getInstance();
        mDatabase = firebaseDatabase.getReference().child("RiderOrdersList").child(user_id).child("CurrentOrders");
        Query query=mDatabase.orderByKey();
        FirebaseRecyclerOptions<ROrderModel> options =
                new FirebaseRecyclerOptions.Builder<ROrderModel>()
                        .setQuery(query, ROrderModel.class)
                        .build();


        final FirebaseRecyclerAdapter<ROrderModel,RJobsFragment.Orderviewholder> fRadapter =
                new FirebaseRecyclerAdapter<ROrderModel, RJobsFragment.Orderviewholder>(options)
                {
                    @Override
                    protected void onBindViewHolder(RJobsFragment.Orderviewholder holder, int position, final ROrderModel model) {

                        // progressDialog.setVisibility(View.GONE);
                        //   initViewMenuOrderExtraItem(view,false);

                        holder.r_hotel_name.setText(model.getRestaurants());
                        holder.r_order_number.setText(model.getOrder_id());
                        holder.r_total_bil_tv.setText(model.getSymbol()+" "+model.getPrice());

                        holder.accept_btn.setOnClickListener(new View.OnClickListener() {
                            @Override
                            public void onClick(View v) {
                                getserverkey(model.getOrder_id());
                                acceptRiderOrder(model.getOrder_id());
                            }
                        });
                       // holder.accept_btn.setVisibility(View.GONE);



                    }

                    @Override
                    public RJobsFragment.Orderviewholder onCreateViewHolder(ViewGroup parent, int viewType) {
                        View view = LayoutInflater.from(parent.getContext())
                                .inflate(R.layout.r_row_item_job,parent,false);

                        return new RJobsFragment.Orderviewholder(view);
                    }


                    @Override
                    public void onAttachedToRecyclerView(RecyclerView recyclerView) {
                        super.onAttachedToRecyclerView(recyclerView);
                        progressDialog.setVisibility(View.GONE);
                        transparent_layer.setVisibility(View.GONE);
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

        current_job_rv.setAdapter(fRadapter);
        fRadapter.notifyDataSetChanged();

       /* RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();

        Calendar c = Calendar.getInstance();
        System.out.println("Current time => "+c.getTime());

        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        String formattedDate = df.format(c.getTime());

        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("datetime",formattedDate);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jobRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_RIDER_ORDERS, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                Log.d("JSONPost", response.toString());
                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));
                    if(code_id!=200){
                        progressDialog.setVisibility(View.GONE);
                        transparent_layer.setVisibility(View.GONE);
                        Log.e("Status","ofline");
                    }
                    else if(code_id == 200) {

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        JSONArray jsonarray = json.getJSONArray("PendingOrders");

                        for (int i = 0; i < jsonarray.length(); i++) {

                            JSONObject json1 = jsonarray.getJSONObject(i);
                            JSONObject orderObj = json1.getJSONObject("Order");
                            JSONObject riderObj = json1.getJSONObject("RiderOrder");
                            JSONObject restaurantObj = orderObj.getJSONObject("Restaurant");
                            JSONObject currencyObj = restaurantObj.getJSONObject("Currency");
                            JSONObject restaurantLocObj = restaurantObj.getJSONObject("RestaurantLocation");

                            JSONObject userInfoObj = orderObj.getJSONObject("UserInfo");
                            JSONObject userAddressObj = orderObj.getJSONObject("Address");

                          //  JSONObject jsonObjTax = json1.getJSONObject("Tax");

                            RiderJobModel riderJobModel = new RiderJobModel();

                            riderJobModel.setOrder_number(riderObj.optString("order_id"));
                            riderJobModel.setHotel_name(restaurantObj.optString("name"));

                            String zip = restaurantLocObj.optString("zip");
                            String city = restaurantLocObj.optString("city");
                            String state = restaurantLocObj.optString("state");
                            String country = restaurantLocObj.optString("country");

                            riderJobModel.setHotel_address(zip+", "+city+", "+state+", "+country);
                            String cash_status = orderObj.optString("cod");

                            if(cash_status.equalsIgnoreCase("0")) {
                                riderJobModel.setOrder_cash_status("Cash on Delivery");
                            }
                            else {
                                riderJobModel.setOrder_cash_status("Credit Card");
                            }

                            String symbol = currencyObj.optString("symbol");
                            riderJobModel.setRider_symbol(symbol);
                            riderJobModel.setOrder_price(orderObj.optString("price"));

                            /// Set Time Formate

                            String date_time = orderObj.optString("created");
                            //  String date = date_time.substring(0,10);
                            //  String time = date_time.substring(11,19);

                            StringTokenizer tk = new StringTokenizer(date_time);
                            String date = tk.nextToken();
                            String time = tk.nextToken();

                            SimpleDateFormat sdf = new SimpleDateFormat("hh:mm:ss");
                            SimpleDateFormat sdfs = new SimpleDateFormat("hh:mm a");
                            Date dt;
                            try {
                                dt = sdf.parse(time);
                                System.out.println("Time Display: " + sdfs.format(dt));
                                String finalTime = sdfs.format(dt);
                                riderJobModel.setOrder_time(finalTime);
                                // <-- I got result here
                            } catch (ParseException e) {
                                // TODO Auto-generated catch block
                                e.printStackTrace();
                            }

                            riderJobList.add(riderJobModel);

                        }
                        if(riderJobList!=null) {
                            progressDialog.setVisibility(View.GONE);
                            transparent_layer.setVisibility(View.GONE);
                            dataAdapter = new RPendingJobAdapter(riderJobList, getActivity(),RJobsFragment.this,customProgress,
                                    progressDialog,transparent_layer);
                            pending_job_rv.setAdapter(dataAdapter);
                            dataAdapter.notifyDataSetChanged();
                           dataAdapter.setOnItemClickListner(new RPendingJobAdapter.OnItemClickListner() {
                               @Override
                               public void OnItemClicked(View view, final int position) {

                               }
                           });
                        }

                    }


                }catch (JSONException e){

                    e.getCause();
                    Log.d("JSON",e.getMessage().toString());
                }



            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                progressDialog.setVisibility(View.GONE);
                transparent_layer.setVisibility(View.GONE);
            }
        });

        jobRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));

        queue.add(jobRequest);*/

    }

    class Orderviewholder extends RecyclerView.ViewHolder{
        public TextView r_hotel_name,r_order_number,r_total_bil_tv;
        RelativeLayout r_job_main;
        Button accept_btn;


        public Orderviewholder(View itemView) {

            super(itemView);

            r_hotel_name = itemView.findViewById(R.id.r_hotel_name);
            r_order_number = itemView.findViewById(R.id.r_order_number);
          //  r_order_address = itemView.findViewById(R.id.r_order_address);
            r_total_bil_tv = itemView.findViewById(R.id.r_total_bil_tv);
          //  card_detail_tv = itemView.findViewById(R.id.card_detail_tv);
           // time_tv = itemView.findViewById(R.id.time_tv);

            r_job_main = itemView.findViewById(R.id.r_job_main);
            accept_btn = itemView.findViewById(R.id.accept_btn);


        }
    }


    private void acceptRiderOrder(final String order_id) {

        customProgress.start();
        progressDialog.setVisibility(View.VISIBLE);
        transparent_layer.setVisibility(View.VISIBLE);
        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("order_id", order_id);
            jsonObject.put("status", "1");
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.Accept_RIDER_ORDER, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String str = response.toString();
                try {
                    JSONObject jsonObject1 = new JSONObject(str);

                    int code = Integer.parseInt(jsonObject1.optString("code"));
                    if (code == 200) {


                        progressDialog.setVisibility(View.GONE);
                        transparent_layer.setVisibility(View.GONE);
                        addToPendingOrders();


                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.setVisibility(View.GONE);
                transparent_layer.setVisibility(View.GONE);
              //  Toast.makeText(getContext(), error.toString(), Toast.LENGTH_SHORT).show();

            }
        }){
            @Override
            public String getBodyContentType() {
                return "application/json; charset=utf-8";
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap<String, String> headers = new HashMap<String, String>();
                headers.put("api-key", "2a5588cf-4cf3-4f1c-9548-cc1db4b54ae3");
                return headers;
            }
        };

        queue.add(jsonObjectRequest);
    }

    public void addToPendingOrders(){

        final DatabaseReference add_to_onother=FirebaseDatabase.getInstance().getReference()
                .child("RiderOrdersList").child(user_id);
        add_to_onother.child("CurrentOrders").child(serverkey).addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {

                ROrderModel modelClass=dataSnapshot.getValue(ROrderModel.class);
                HashMap<String,String> map=new HashMap<>();
                map.put("order_id",modelClass.getOrder_id());
                map.put("price",modelClass.getPrice());
                map.put("restaurants",modelClass.getRestaurants());
                map.put("status",modelClass.getStatus());
                map.put("symbol",modelClass.getSymbol());

                add_to_onother.child("PendingOrders").child(serverkey)
                        .setValue(map).addOnCompleteListener(new OnCompleteListener<Void>() {
                    @Override
                    public void onComplete(@NonNull Task<Void> task) {


                        add_to_onother.child("CurrentOrders").child(serverkey).removeValue();

                    }
                });

            }

            @Override
            public void onCancelled(DatabaseError databaseError) {

            }
        });
    }


    public void getPendingOrderList(){

     /*   progressDialog.setVisibility(View.VISIBLE);
        transparent_layer.setVisibility(View.VISIBLE);

        riderCurrentJobList = new ArrayList<>();

        progressDialog.setVisibility(View.VISIBLE);*/
        firebaseDatabase = FirebaseDatabase.getInstance();
        mDatabase = firebaseDatabase.getReference().child("RiderOrdersList").child(user_id).child("PendingOrders");
        Query query=mDatabase.orderByKey();
        FirebaseRecyclerOptions<ROrderModel> options =
                new FirebaseRecyclerOptions.Builder<ROrderModel>()
                        .setQuery(query, ROrderModel.class)
                        .build();


        final FirebaseRecyclerAdapter<ROrderModel,RJobsFragment.Orderviewholder> fRadapter =
                new FirebaseRecyclerAdapter<ROrderModel, RJobsFragment.Orderviewholder>(options)
                {
                    @Override
                    protected void onBindViewHolder(RJobsFragment.Orderviewholder holder, int position, final ROrderModel model) {

                        // progressDialog.setVisibility(View.GONE);
                        //   initViewMenuOrderExtraItem(view,false);

                            holder.r_hotel_name.setText(model.getRestaurants());
                            holder.r_order_number.setText("Order #"+model.getOrder_id());
                            holder.r_total_bil_tv.setText(model.getSymbol()+" "+model.getPrice());
                            holder.accept_btn.setVisibility(View.GONE);

                            holder.r_job_main.setOnClickListener(new View.OnClickListener() {
                                @Override
                                public void onClick(View v) {
                                    FLAG_CURRENT_JOB = true;

                                 //   getserverkeyCurrent(model.getOrder_id());

                                    SharedPreferences.Editor editor = pending_job_pref.edit();
                                    editor.putString(PreferenceClass.RIDER_HOTEL_NAME,model.getRestaurants());
                                    editor.putString(PreferenceClass.RIDER_ORDER_NUMBER,model.getOrder_id());
                                    editor.putString(PreferenceClass.RIDER_ORDER_SYMBOL,model.getSymbol());
                                    editor.putString(PreferenceClass.RIDER_TOTAL_PAYMENT,model.getPrice());
                                //    editor.putString(PreferenceClass.SERVER_KEY,KEY);
                                    editor.commit();

                                    Fragment restaurantMenuItemsFragment = new ROrderDetailFragment();
                                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                    transaction.add(R.id.main_container, restaurantMenuItemsFragment,"parent").commit();
                                }
                            });



                    }

                    @Override
                    public RJobsFragment.Orderviewholder onCreateViewHolder(ViewGroup parent, int viewType) {
                        View view = LayoutInflater.from(parent.getContext())
                                .inflate(R.layout.r_row_item_job,parent,false);

                        return new RJobsFragment.Orderviewholder(view);
                    }


                    @Override
                    public void onAttachedToRecyclerView(RecyclerView recyclerView) {
                        super.onAttachedToRecyclerView(recyclerView);
                        progressDialog.setVisibility(View.GONE);
                        transparent_layer.setVisibility(View.GONE);
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

        pending_job_rv.setAdapter(fRadapter);
        fRadapter.notifyDataSetChanged();


    /*    Calendar c = Calendar.getInstance();
        System.out.println("Current time => "+c.getTime());

        SimpleDateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        String formattedDate = df.format(c.getTime());

        RequestQueue queue = Volley.newRequestQueue(getContext());

        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("datetime",formattedDate);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jobRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_RIDER_ORDERS, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {


                Log.d("JSONPost", response.toString());
                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                    if(code_id!=200){
                        progressDialog.setVisibility(View.GONE);
                        transparent_layer.setVisibility(View.GONE);
                        Log.e("Status","ofline");
                    }

                   else if(code_id == 200) {

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        JSONArray jsonarray = json.getJSONArray("ActiveOrders");

                        for (int i = 0; i < jsonarray.length(); i++) {

                            JSONObject json1 = jsonarray.getJSONObject(i);
                            JSONObject orderObj = json1.getJSONObject("Order");
                            JSONObject riderObj = json1.getJSONObject("RiderOrder");
                            JSONObject restaurantObj = orderObj.getJSONObject("Restaurant");
                            JSONObject currencyObj = restaurantObj.getJSONObject("Currency");
                            JSONObject restaurantLocObj = restaurantObj.getJSONObject("RestaurantLocation");



                            JSONObject userInfoObj = orderObj.getJSONObject("UserInfo");
                            JSONObject userAddressObj = orderObj.getJSONObject("Address");
                            String symbol = currencyObj.optString("symbol");
                            //  JSONObject jsonObjTax = json1.getJSONObject("Tax");

                            RiderJobModel riderJobModel = new RiderJobModel();


                            //All User Info

                            riderJobModel.setUser_f_name(userInfoObj.optString("first_name"));
                            riderJobModel.setUser_l_name(userInfoObj.optString("last_name"));
                            riderJobModel.setUser_phone_number(userInfoObj.optString("phone"));
                            riderJobModel.setUser_street(userAddressObj.optString("street"));
                            riderJobModel.setUser_apartment(userAddressObj.optString("apartment"));
                            riderJobModel.setUser_city(userAddressObj.optString("city"));
                            riderJobModel.setUser_state(userAddressObj.optString("state"));
                            riderJobModel.setUser_lat(userAddressObj.optString("lat"));
                            riderJobModel.setUser_long(userAddressObj.optString("long"));


                            /// All Hotel Detail Data

                            riderJobModel.setHotel_lat(restaurantLocObj.optString("lat"));
                            riderJobModel.setHotel_long(restaurantLocObj.optString("long"));


                            riderJobModel.setOrder_number(riderObj.optString("order_id"));
                            riderJobModel.setHotel_name(restaurantObj.optString("name"));
                            riderJobModel.setHotel_phone_number(restaurantObj.optString("phone"));
                            riderJobModel.setOrder_tax(orderObj.optString("tax"));
                            riderJobModel.setOrder_delivery_fee(restaurantObj.optString("delivery_fee"));


                            riderJobModel.setHotel_zip(restaurantLocObj.optString("zip"));
                            riderJobModel.setHotel_city(restaurantLocObj.optString("city"));
                            riderJobModel.setHotel_state(restaurantLocObj.optString("state"));
                            riderJobModel.setHotel_country(restaurantLocObj.optString("country"));
                            String zip = restaurantLocObj.optString("zip");
                            String city = restaurantLocObj.optString("city");
                            String state = restaurantLocObj.optString("state");
                            String country = restaurantLocObj.optString("country");

                            riderJobModel.setHotel_address(zip+", "+city+", "+state+", "+country);
                            String cash_status = orderObj.optString("cod");

                            if(cash_status.equalsIgnoreCase("1")) {
                                riderJobModel.setOrder_cash_status("Credit Card");
                            }
                            else {
                                riderJobModel.setOrder_cash_status("Cash on Delivery");
                            }


                            riderJobModel.setRider_symbol(currencyObj.optString("symbol"));
                            riderJobModel.setOrder_price(orderObj.optString("price"));

                            /// Set Time Formate

                            String date_time = orderObj.optString("created");
                            //  String date = date_time.substring(0,10);
                            //  String time = date_time.substring(11,19);

                            StringTokenizer tk = new StringTokenizer(date_time);
                            String date = tk.nextToken();
                            String time = tk.nextToken();

                            SimpleDateFormat sdf = new SimpleDateFormat("hh:mm:ss");
                            SimpleDateFormat sdfs = new SimpleDateFormat("hh:mm a");
                            Date dt;
                            try {
                                dt = sdf.parse(time);
                                System.out.println("Time Display: " + sdfs.format(dt));
                                String finalTime = sdfs.format(dt);
                                riderJobModel.setOrder_time(finalTime);
                                // <-- I got result here
                            } catch (ParseException e) {
                                // TODO Auto-generated catch block
                                e.printStackTrace();
                            }

                            riderCurrentJobList.add(riderJobModel);

                        }
                        if(riderCurrentJobList!=null) {
                            progressDialog.setVisibility(View.GONE);
                            transparent_layer.setVisibility(View.GONE);
                            dataAdapter2 = new RCurrentJobAdapter(riderCurrentJobList, getActivity());
                            current_job_rv.setAdapter(dataAdapter2);
                            dataAdapter2.notifyDataSetChanged();

                            dataAdapter2.setOnItemClickListner(new RPendingJobAdapter.OnItemClickListner() {
                                @Override
                                public void OnItemClicked(View view, int position) {


                                    SharedPreferences.Editor editor = pending_job_pref.edit();

                                    editor.putString(PreferenceClass.RIDER_ORDER_NUMBER,riderCurrentJobList.get(position).getOrder_number());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_NAME,riderCurrentJobList.get(position).getHotel_name());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_ZIP,riderCurrentJobList.get(position).getHotel_zip());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_CITY,riderCurrentJobList.get(position).getHotel_city());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_STATE,riderCurrentJobList.get(position).getHotel_state());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_COUNTRY,riderCurrentJobList.get(position).getHotel_country());

                                    editor.putString(PreferenceClass.RIDER_PAYMENT_STATUS,riderCurrentJobList.get(position).getOrder_cash_status());
                                    editor.putString(PreferenceClass.RIDER_TOTAL_PAYMENT,riderCurrentJobList.get(position).getOrder_price());
                                    editor.putString(PreferenceClass.RIDER_TIME,riderCurrentJobList.get(position).getOrder_time());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_PHONE,riderCurrentJobList.get(position).getHotel_phone_number());
                                    editor.putString(PreferenceClass.RIDER_ORDER_SYMBOL,riderCurrentJobList.get(position).getRider_symbol());
                                    editor.putString(PreferenceClass.RIDER_USER_F_NAME,riderCurrentJobList.get(position).getUser_f_name());
                                    editor.putString(PreferenceClass.RIDER_USER_L_NAME,riderCurrentJobList.get(position).getUser_l_name());
                                    editor.putString(PreferenceClass.RIDER_USER_PHONE,riderCurrentJobList.get(position).getUser_phone_number());
                                    editor.putString(PreferenceClass.RIDER_USER_STREET,riderCurrentJobList.get(position).getUser_street());
                                    editor.putString(PreferenceClass.RIDER_USER_APARTMENT,riderCurrentJobList.get(position).getUser_apartment());
                                    editor.putString(PreferenceClass.RIDER_USER_CITY,riderCurrentJobList.get(position).getUser_city());
                                    editor.putString(PreferenceClass.RIDER_USER_STATE,riderCurrentJobList.get(position).getUser_state());
                                    editor.putString(PreferenceClass.RIDER_ORDER_TAX,riderCurrentJobList.get(position).getOrder_tax());
                                    editor.putString(PreferenceClass.RIDER_ORDER_DELIVER_FEE,riderCurrentJobList.get(position).getOrder_delivery_fee());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_LAT,riderCurrentJobList.get(position).getHotel_lat());
                                    editor.putString(PreferenceClass.RIDER_HOTEL_LONG,riderCurrentJobList.get(position).getHotel_long());
                                    editor.putString(PreferenceClass.RIDER_USER_LAT,riderCurrentJobList.get(position).getUser_lat());
                                    editor.putString(PreferenceClass.RIDER_USER_LONG,riderCurrentJobList.get(position).getUser_long());

                                    editor.commit();

                                    FLAG_CURRENT_JOB = true;
                                    Fragment restaurantMenuItemsFragment = new ROrderDetailFragment();
                                    FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                    transaction.add(R.id.main_container, restaurantMenuItemsFragment,"parent").commit();

                                }
                            });
                        }


                    }


                }catch (JSONException e){
                    progressDialog.setVisibility(View.GONE);
                    transparent_layer.setVisibility(View.GONE);
                    e.getCause();
                    Log.d("JSON",e.getMessage().toString());
                }



            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                progressDialog.setVisibility(View.GONE);
                transparent_layer.setVisibility(View.GONE);
            }
        });
        jobRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(jobRequest);
*/
    }





    public void getserverkey(String order_id){
        DatabaseReference mref= FirebaseDatabase.getInstance().getReference();

        final Query query2 =mref.child("RiderOrdersList").child(user_id).child("CurrentOrders").orderByChild("order_id").equalTo(order_id);
        query2.addListenerForSingleValueEvent(new ValueEventListener() {
            @Override
            public void onDataChange(DataSnapshot dataSnapshot) {
                for (DataSnapshot nodeDataSnapshot : dataSnapshot.getChildren()) {
                    serverkey = nodeDataSnapshot.getKey(); // this key is `K1NRz9l5PU_0CFDtgXz`
                }

            }

            @Override
            public void onCancelled(DatabaseError databaseError) {

            }
        });


    }


    public void shouwOnlineStatus(){

        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("user_id",user_id);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.ONLINE_STATUS, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String str = response.toString();
                try {
                    JSONObject jsonObject1 = new JSONObject(str);

                    int code = Integer.parseInt(jsonObject1.optString("code"));
                    if (code == 200){

                        int msg = Integer.parseInt(jsonObject1.optString("msg"));

                        SharedPreferences.Editor editor = pending_job_pref.edit();

                        if(msg == 1){

                            r_check_out_btn.setText("Check Out");
                            editor.putString(PreferenceClass.RIDER_ONLINE_STATUS,"1");
                        }
                        else {
                            r_check_out_btn.setText("Check In");
                            editor.putString(PreferenceClass.RIDER_ONLINE_STATUS,"0");
                        }

                        editor.commit();

                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

             //   Toast.makeText(getContext(),error.toString(),Toast.LENGTH_SHORT).show();

            }
        }){
            @Override
            public String getBodyContentType() {
                return "application/json; charset=utf-8";
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap<String, String> headers = new HashMap<String, String>();
                headers.put("api-key", "2a5588cf-4cf3-4f1c-9548-cc1db4b54ae3");
                return headers;
            }
        };
        jsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(jsonObjectRequest);

    }

    public void showComingRiderShift(){
        progressDialog.setVisibility(View.VISIBLE);
        transparent_layer.setVisibility(View.VISIBLE);
        Calendar c = Calendar.getInstance();
        System.out.println("Current time => " + c.getTime());

        SimpleDateFormat df = new SimpleDateFormat("yyyy-MMM-dd");
        String formattedDate = df.format(c.getTime());

        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("datetime",formattedDate);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_UP_COMMING_RIDER_SHIFTS, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String str = response.toString();

                try {
                    JSONObject jsonObject1 = new JSONObject(str);
                    int code_id  = Integer.parseInt(jsonObject1.optString("code"));
                    if(code_id == 200){
                        JSONArray jsonArray = jsonObject1.getJSONArray("msg");

                        for(int i =0; i<jsonArray.length();i++){

                            JSONObject jsonObject2 = jsonArray.getJSONObject(i);
                            JSONObject jsonObject3 = jsonObject2.getJSONObject("RiderTiming");

                            String starting_time = jsonObject3.optString("starting_time");
                            String ending_time = jsonObject3.optString("ending_time");



                            SharedPreferences.Editor editor = pending_job_pref.edit();
                            editor.putString(PreferenceClass.TIMING_ID,jsonObject3.optString("id"));
                            editor.commit();
                            progressDialog.setVisibility(View.GONE);
                            transparent_layer.setVisibility(View.GONE);
                            SimpleDateFormat sdf = new SimpleDateFormat("hh:mm:ss");
                            SimpleDateFormat sdfs = new SimpleDateFormat("hh:mm a");
                            Date dt,dt2;
                            try {
                                dt = sdf.parse(starting_time);
                                dt2 = sdf.parse(ending_time);
                                System.out.println("Time Display: " + sdfs.format(dt));
                                String finalTime = sdfs.format(dt);
                                String finalTime2 = sdfs.format(dt2);

                                time_check_tv.setText(finalTime+"-"+finalTime2);
                                // <-- I got result here
                            } catch (ParseException e) {
                                // TODO Auto-generated catch block
                                e.printStackTrace();
                            }

                        }

                    }

                } catch (JSONException e) {
                    e.printStackTrace();
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
              //  Toast.makeText(getContext(),error.toString(),Toast.LENGTH_SHORT).show();
                progressDialog.setVisibility(View.GONE);
                transparent_layer.setVisibility(View.GONE);

            }
        }){
            @Override
            public String getBodyContentType() {
                return "application/json; charset=utf-8";
            }

            @Override
            public Map<String, String> getHeaders() throws AuthFailureError {
                HashMap<String, String> headers = new HashMap<String, String>();
                headers.put("api-key", "2a5588cf-4cf3-4f1c-9548-cc1db4b54ae3");
                return headers;
            }
        };
        jsonObjectRequest.setRetryPolicy(new DefaultRetryPolicy(5000,
                DefaultRetryPolicy.DEFAULT_MAX_RETRIES,
                DefaultRetryPolicy.DEFAULT_BACKOFF_MULT));
        queue.add(jsonObjectRequest);

    }


    private void fn_permission() {
        if ((ContextCompat.checkSelfPermission(getContext(), android.Manifest.permission.ACCESS_FINE_LOCATION) != PackageManager.PERMISSION_GRANTED)) {

            if ((ActivityCompat.shouldShowRequestPermissionRationale(getActivity(), android.Manifest.permission.ACCESS_FINE_LOCATION))) {


            } else {
                ActivityCompat.requestPermissions(getActivity(), new String[]{android.Manifest.permission.ACCESS_FINE_LOCATION

                        },
                        REQUEST_PERMISSIONS);

            }
        } else {
            boolean_permission = true;
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, String[] permissions, int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);

        switch (requestCode) {
            case REQUEST_PERMISSIONS: {
                if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                    boolean_permission = true;

                } else {
                    Toast.makeText(getContext(), "Please allow the permission", Toast.LENGTH_LONG).show();

                }
            }
        }
    }

  /*  private BroadcastReceiver broadcastReceiver = new BroadcastReceiver() {
        @Override
        public void onReceive(Context context, Intent intent) {

            latitude = Double.valueOf(intent.getStringExtra("latutide"));
            longitude = Double.valueOf(intent.getStringExtra("longitude"));

            previousLat = String.valueOf(Double.parseDouble(previousLat)+Double.parseDouble("0.0"));
            previousLong = String.valueOf(Double.parseDouble(previousLong)+Double.parseDouble("0.0"));;
            mDatabase.keepSynced(true);
            mDatabase.setValue(new TrackingModelClass(""+latitude,""+longitude,previousLat,previousLong));

            previousLat = ""+latitude;
            previousLong = ""+longitude;
          // Toast.makeText(getContext(),latitude+", "+longitude,Toast.LENGTH_SHORT).show();



        }
    };
*/


    @SuppressWarnings("deprecation")
    private boolean checkPlayServices() {
        int resultCode = GooglePlayServicesUtil
                .isGooglePlayServicesAvailable(getContext());
        if (resultCode != ConnectionResult.SUCCESS) {
            if (GooglePlayServicesUtil.isUserRecoverableError(resultCode)) {
                GooglePlayServicesUtil.getErrorDialog(resultCode, getActivity(),
                        PLAY_SERVICES_RESOLUTION_REQUEST).show();
            } else {
                Toast.makeText(getContext(),
                        "This device is not supported.", Toast.LENGTH_LONG)
                        .show();
                //finish();
            }
            return false;
        }
        return true;
    }


    @Override
    public void onResume() {
        super.onResume();
        checkPlayServices();
     //   getContext().registerReceiver(broadcastReceiver, new IntentFilter(UpdateLocation.str_receiver));

      /*  shouwOnlineStatus();
        showComingRiderShift();
        getPendingOrderList();
        getCurrentOrderList();*/
    }

    @Override
    public void onPause() {
        super.onPause();

    }



    @Override
    public void onConnected(@Nullable Bundle bundle) {

    }

    @Override
    public void onConnectionSuspended(int i) {

    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {

    }
}
