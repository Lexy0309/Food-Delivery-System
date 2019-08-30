package com.dinosoftlabs.foodies.android.RActivitiesAndFragments;

import android.content.Context;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.content.ContextCompat;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.ContextMenu;
import android.view.LayoutInflater;
import android.view.MenuInflater;
import android.view.MenuItem;
import android.view.MotionEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RAdapters.RiderShiftAdapter;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;

import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RAdapters.RAvailableTimeAdapter;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderModels.RShiftModel;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RiderModels.RParentModel;
import com.dinosoftlabs.foodies.android.Utils.CustomExpandableListView;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;
import com.dinosoftlabs.foodies.android.Utils.SwipeHelper;
import com.gmail.samehadar.iosdialog.CamomileSpinner;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

/**
 * Created by Nabeel on 1/17/2018.
 */

public class RAvailablityFragment extends Fragment {

    RAvailableTimeAdapter listAdapter;
    CustomExpandableListView customExpandableListView;
    ArrayList<RParentModel> listDataHeader;
    ArrayList<RShiftModel> shiftArray;
    private ArrayList<ArrayList<RShiftModel>> ListChild;
    SharedPreferences sPref;
    ImageView back_icon,order_place;
   // ProgressBar scheduleProgress;

    CamomileSpinner customProgress;
    public RelativeLayout progressDialog;

    RelativeLayout myShift_div,openShift_div,transparent_layer;
    TextView myShift_tv,openShift_tv;

    private RecyclerView shiftRecyclerView,shiftRecyclerView2;
    RecyclerView.LayoutManager recyclerViewlayoutManager,recyclerViewlayoutManager2;
    private RiderShiftAdapter riderShiftAdapter;

    String OpenShift = "OpenShift";
    String RiderTiming = "RiderTiming";

    public static boolean FLAG_RIDER_TIMING,IS_TIMING_ID;
    String user_id,fDate;
    SwipeRefreshLayout refresh_layout;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {

        View v = inflater.inflate(R.layout.rider_availablity_fragment, container, false);
        FLAG_RIDER_TIMING = true;
        sPref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        FrameLayout availablity_main_container = v.findViewById(R.id.availablity_main_container);
        FontHelper.applyFont(getContext(),availablity_main_container, AllConstants.verdana);

        user_id = sPref.getString(PreferenceClass.pre_user_id,"");
        Date cDate = new Date();
        fDate = new SimpleDateFormat("yyyy-MM-dd").format(cDate);

        availablity_main_container.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View view, MotionEvent motionEvent) {
                return true;
            }
        });
        init(v);
        return v;

    }
    @SuppressWarnings("deprecation")
    public void init(View v){
        transparent_layer = v.findViewById(R.id.transparent_layer);
        customProgress = v.findViewById(R.id.customProgress);
        progressDialog = v.findViewById(R.id.progressDialog);
        customProgress.start();

        shiftRecyclerView = v.findViewById(R.id.shiftRecyclerView);
        shiftRecyclerView2 = v.findViewById(R.id.shiftRecyclerView2);
        //progressBar = view.findViewById(R.id.restaurantProgress);
        shiftRecyclerView.setHasFixedSize(true);
        shiftRecyclerView2.setHasFixedSize(true);

        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        recyclerViewlayoutManager2 = new LinearLayoutManager(getContext());

        shiftRecyclerView.setLayoutManager(recyclerViewlayoutManager);
        shiftRecyclerView2.setLayoutManager(recyclerViewlayoutManager2);

      /*  if(FLAG_RIDER_TIMING) {
           swipEditDel();
        }
*/
        swipEditDel();
        openShift_tv = v.findViewById(R.id.openShift_tv);
        myShift_tv = v.findViewById(R.id.myShift_tv);
        openShift_div = v.findViewById(R.id.openShift_div);
        myShift_div = v.findViewById(R.id.myShift_div);

        order_place = v.findViewById(R.id.order_place);
        getAvailableTimeListRiderTiming(RiderTiming);
        back_icon = v.findViewById(R.id.back_icon);
        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                RProfileFragment rJobsFragment = new RProfileFragment();
                FragmentTransaction transaction = getFragmentManager().beginTransaction();
                transaction.replace(R.id.availablity_main_container, rJobsFragment);
                transaction.addToBackStack(null);
                transaction.commit();
            }
        });

        order_place.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                Fragment restaurantMenuItemsFragment = new RiderAddAvailabilityFragment();
                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                transaction.add(R.id.availablity_main_container, restaurantMenuItemsFragment,"ParentFragment").commit();
                FLAG_RIDER_TIMING = true;

            }
        });

        openShift_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                openShift_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_white_left));
                myShift_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_red_right));
                myShift_tv.setTextColor(ContextCompat.getColor(getContext(),R.color.colorWhite));
                openShift_tv.setTextColor(ContextCompat.getColor(getContext(),R.color.colorRed));
                FLAG_RIDER_TIMING = false;

                getAvailableTimeListOpenShift();

            }
        });
        myShift_div.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {
                myShift_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_white));
                openShift_div.setBackground(getResources().getDrawable(R.drawable.round_shape_btn_red));
                myShift_tv.setTextColor(ContextCompat.getColor(getContext(),R.color.colorRed));
                openShift_tv.setTextColor(ContextCompat.getColor(getContext(), R.color.colorWhite));
               // openShift_tv.setTextColor(getResources().getColor(R.color.colorWhite));
                FLAG_RIDER_TIMING = true;

                getAvailableTimeListRiderTiming(RiderTiming);
              //  FLAG_DEALS = false;
              //  getAllOrderParser(status_active);
                // decline_tv.setTextColor(getResources().getColor(R.color.or_color_name));
                //   accept_tv.setTextColor(getResources().getColor(R.color.colorWhite));
            }
        });


        refresh_layout = v.findViewById(R.id.refresh_shift);
        refresh_layout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {

                getAvailableTimeListRiderTiming(RiderTiming);
                // getRestList();
                refresh_layout.setRefreshing(false);

            }
        });

    }

    public void getAvailableTimeListRiderTiming(final String riderTiming){
        progressDialog.setVisibility(View.VISIBLE);
        transparent_layer.setVisibility(View.VISIBLE);
        shiftArray = new ArrayList<>();

        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("date",fDate);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest timeRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_RIDER_TIMING, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                    if(code_id == 200) {

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        JSONObject msgObj = json.getJSONObject("msg");

                            JSONArray riderTimingArray = msgObj.getJSONArray(riderTiming);

                            for (int i =0;i<riderTimingArray.length();i++){

                                JSONObject allJsonObj = riderTimingArray.getJSONObject(i);

                                JSONArray allTimingArray = allJsonObj.getJSONArray("timing");

                                for (int j = 0 ;j<allTimingArray.length();j++){

                                    JSONObject allJsonObj2 = allTimingArray.getJSONObject(j);
                                    RShiftModel shiftModel = new RShiftModel();
                                    shiftModel.setStart_time(allJsonObj2.optString("starting_time"));
                                    shiftModel.setEnd_time(allJsonObj2.optString("ending_time"));
                                    shiftModel.setId(allJsonObj2.optString("id"));
                                    shiftModel.setDate(allJsonObj2.optString("date"));
                                    shiftModel.setStart_time(allJsonObj2.optString("starting_time"));
                                    shiftModel.setEnd_time(allJsonObj2.optString("ending_time"));
                                    shiftModel.setConfirm(allJsonObj2.optString("confirm"));
                                    shiftModel.setAdmin_confirm(allJsonObj2.optString("admin_confirm"));


                                    shiftArray.add(shiftModel);
                                    /*listChildData.add(childModel);
                                    ListChild.add(listChildData);*/
                                }

                            }

                        riderShiftAdapter = new RiderShiftAdapter(shiftArray,getContext(),RAvailablityFragment.this);
                        shiftRecyclerView.setAdapter(riderShiftAdapter);
                        progressDialog.setVisibility(View.GONE);
                        shiftRecyclerView.setVisibility(View.VISIBLE);
                        shiftRecyclerView2.setVisibility(View.GONE);
                        transparent_layer.setVisibility(View.GONE);

                    }

                    }catch (Exception e){
                    e.getMessage();
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                //scheduleProgress.setVisibility(View.GONE);
               // Toast.makeText(getContext(),error.toString(),Toast.LENGTH_SHORT).show();
                progressDialog.setVisibility(View.GONE);
                transparent_layer.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());

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

        queue.add(timeRequest);

    }


    public void getAvailableTimeListOpenShift(){
        progressDialog.setVisibility(View.VISIBLE);
        shiftArray = new ArrayList<>();

        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("date",fDate);
        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest timeRequest = new JsonObjectRequest(Request.Method.POST, Config.SHOW_RIDER_TIMING, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id  = Integer.parseInt(jsonResponse.optString("code"));

                    if(code_id == 200) {

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        JSONObject msgObj = json.getJSONObject("msg");

                        JSONArray riderTimingArray = msgObj.getJSONArray(OpenShift);

                        for (int i =0;i<riderTimingArray.length();i++){

                            JSONObject allJsonObj = riderTimingArray.getJSONObject(i);

                            RShiftModel shiftModel = new RShiftModel();

                            JSONArray allTimingArray = allJsonObj.getJSONArray("timing");

                            for (int j = 0 ;j<allTimingArray.length();j++){

                                JSONObject allJsonObj2 = allTimingArray.getJSONObject(j);

                                shiftModel.setStart_time(allJsonObj2.optString("starting_time"));
                                shiftModel.setEnd_time(allJsonObj2.optString("ending_time"));
                                shiftModel.setId(allJsonObj2.optString("id"));
                                shiftModel.setDate(allJsonObj2.optString("date"));
                                shiftModel.setStart_time(allJsonObj2.optString("starting_time"));
                                shiftModel.setEnd_time(allJsonObj2.optString("ending_time"));
                                shiftModel.setConfirm(allJsonObj2.optString("confirm"));


                                shiftArray.add(shiftModel);
                                    /*listChildData.add(childModel);
                                    ListChild.add(listChildData);*/
                            }

                        }

                        // listAdapter = new RAvailableTimeAdapter(getContext(), listDataHeader, ListChild);
                        //scheduleProgress.setVisibility(View.GONE);
                        riderShiftAdapter = new RiderShiftAdapter(shiftArray,getContext(),RAvailablityFragment.this);
                        shiftRecyclerView2.setAdapter(riderShiftAdapter);
                        riderShiftAdapter.notifyDataSetChanged();
                        progressDialog.setVisibility(View.GONE);
                        shiftRecyclerView.setVisibility(View.GONE);
                        shiftRecyclerView2.setVisibility(View.VISIBLE);
                        riderShiftAdapter.setOnItemClickListner(new RiderShiftAdapter.OnItemClickListner() {
                            @Override
                            public void OnItemClicked(View view, final int position) {


                            }
                        });

                        // setting list adapter
                      /*  customExpandableListView.setAdapter(listAdapter);
                        customExpandableListView.setOnItemLongClickListener(new AdapterView.OnItemLongClickListener() {
                            @Override
                            public boolean onItemLongClick(AdapterView<?> adapterView, View view, int pos, long id) {

                                if (ExpandableListView.getPackedPositionType(id) == ExpandableListView.PACKED_POSITION_TYPE_CHILD) {
                                    int groupPosition = ExpandableListView.getPackedPositionGroup(id);
                                    int childPosition = ExpandableListView.getPackedPositionChild(id);
                                    PopupMenu p = new PopupMenu(getContext(), view, Gravity.RIGHT);
                                    p.getMenuInflater().inflate(R.menu.context_menu, p.getMenu());
                                    p.show();
                                    Toast.makeText(getContext(),"Parent"+groupPosition+""+childPosition,Toast.LENGTH_SHORT).show();

                                    return true;
                                }

                                return false;
                            }
                        });*/

                       /* customExpandableListView.setOnTouchListener(new View.OnTouchListener() {
                            @Override
                            public boolean onTouch(View view, MotionEvent motionEvent) {

                                switch(motionEvent.getAction())
                                {
                                    case MotionEvent.ACTION_DOWN:
                                        x1 = motionEvent.getX();
                                        break;
                                    case MotionEvent.ACTION_UP:
                                        x2 = motionEvent.getX();
                                        float deltaX = x2 - x1;

                                        if (Math.abs(deltaX) > MIN_DISTANCE)
                                        {
                                            // Left to Right swipe action
                                            if (x2 > x1)
                                            {
                                                Toast.makeText(getContext(), "Left to Right swipe [Next]", Toast.LENGTH_SHORT).show ();
                                            }

                                            // Right to left swipe action
                                            else
                                            {
                                                Toast.makeText(getContext(), "Right to Left swipe [Previous]", Toast.LENGTH_SHORT).show ();
                                            }

                                        }
                                        else
                                        {
                                            // consider as something else - a screen tap for example
                                        }
                                        break;
                                }
                                return false;
                            }
                        });*/

                      /*  for(int l=0; l < listAdapter.getGroupCount(); l++)
                            customExpandableListView.expandGroup(l);*/

                    }

                }catch (Exception e){
                    e.getMessage();
                }

            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                //scheduleProgress.setVisibility(View.GONE);
                Toast.makeText(getContext(),error.toString(),Toast.LENGTH_SHORT).show();
                progressDialog.setVisibility(View.GONE);

            }
        });

        queue.add(timeRequest);

    }


    public void deleteRiderTiming(String id){
        progressDialog.setVisibility(View.VISIBLE);

        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject jsonObject = new JSONObject();
        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("date",fDate);
            jsonObject.put("id",id);

        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, Config.DELETE_RIDER_TIMING, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String strResponse = response.toString();
                try {
                    JSONObject jsonObject1 = new JSONObject(strResponse);
                    String msg = jsonObject1.optString("msg");
                    int code_id  = Integer.parseInt(jsonObject1.optString("code"));
                    if (code_id==200){

                     //   Toast.makeText(getContext(),msg,Toast.LENGTH_SHORT).show();
                        getAvailableTimeListRiderTiming(RiderTiming);
                       // progressDialog.setVisibility(View.GONE);



                       // notifyDataSetChanged();

                    }
                    else {
                        progressDialog.setVisibility(View.GONE);
                      //  Toast.makeText(getContext(),msg.toString(),Toast.LENGTH_SHORT).show();
                    }


                } catch (JSONException e) {
                    e.printStackTrace();
                }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                progressDialog.setVisibility(View.GONE);
            //    Toast.makeText(getContext(),error.toString(),Toast.LENGTH_SHORT).show();

            }
        });

        queue.add(jsonObjectRequest);

    }




    @Override
    public void onCreateContextMenu(ContextMenu menu, View v,
                                    ContextMenu.ContextMenuInfo menuInfo) {
        super.onCreateContextMenu(menu, v, menuInfo);
        MenuInflater inflater = getActivity().getMenuInflater();
        inflater.inflate(R.menu.context_menu, menu);
    }
    @Override
    public boolean onContextItemSelected(MenuItem item) {
        AdapterView.AdapterContextMenuInfo info = (AdapterView.AdapterContextMenuInfo) item
                .getMenuInfo();
        //Get id of item clicked
        // Retrieve the item that was clicked on
        View v = (View) customExpandableListView.getAdapter().getView(
                info.position, null, null);

        switch (item.getItemId()) {
            case R.id.menu_item_del:

                break;
            case R.id.menu_item_confirm:

                break;
            case R.id.menu_item_edit:

                break;
        }

        return true;
    }

    public void swipEditDel(){
        SwipeHelper swipeHelper = new SwipeHelper(getContext(), shiftRecyclerView) {
            @Override
            public void instantiateUnderlayButton(RecyclerView.ViewHolder viewHolder, List<UnderlayButton> underlayButtons) {
                underlayButtons.add(new SwipeHelper.UnderlayButton(
                        "Delete",
                        0,
                        Color.parseColor("#FF3C30"),
                        new SwipeHelper.UnderlayButtonClickListener() {
                            @Override
                            public void onClick(int pos) {
                                // TODO: onDelete
                                deleteRiderTiming(shiftArray.get(pos).getId());

                            }
                        }
                ));

                underlayButtons.add(new SwipeHelper.UnderlayButton(
                        "Edit",
                        0,
                        Color.parseColor("#EBEBEB"),
                        new SwipeHelper.UnderlayButtonClickListener() {
                            @Override
                            public void onClick(int pos) {
                                // TODO: OnTransfer
                                //  Toast.makeText(getContext(),shiftArray.get(pos).getDate(),Toast.LENGTH_SHORT).show();
                                Fragment restaurantMenuItemsFragment = new RiderAddAvailabilityFragment();
                                FragmentTransaction transaction = getChildFragmentManager().beginTransaction();
                                transaction.add(R.id.availablity_main_container, restaurantMenuItemsFragment, "ParentFragment").commit();
                                SharedPreferences.Editor editor = sPref.edit();
                                editor.putString("id_", shiftArray.get(pos).getId());
                                editor.putString("date_", shiftArray.get(pos).getDate()).commit();
                                IS_TIMING_ID = true;

                            }
                        }
                ));
            }


        };
    }



}
