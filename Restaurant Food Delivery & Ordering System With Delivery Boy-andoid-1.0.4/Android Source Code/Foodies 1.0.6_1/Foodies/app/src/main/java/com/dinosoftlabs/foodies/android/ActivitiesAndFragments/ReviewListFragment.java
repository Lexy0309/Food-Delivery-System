package com.dinosoftlabs.foodies.android.ActivitiesAndFragments;

import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentTransaction;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.RestaurantMenuItems.RestaurantMenuItemsFragment;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.dinosoftlabs.foodies.android.Adapters.ShowReviewListAdapter;
import com.dinosoftlabs.foodies.android.Constants.AllConstants;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.RatingListModel;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.RActivitiesAndFragments.RProfileFragment;
import com.dinosoftlabs.foodies.android.Utils.FontHelper;
import com.dinosoftlabs.foodies.android.Utils.TabLayoutUtils;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

/**
 * Created by Nabeel on 12/18/2017.
 */

public class ReviewListFragment extends Fragment {

    ImageView back_icon;
    SharedPreferences sPref;
    ArrayList<RatingListModel> listDataReview;

    RecyclerView.LayoutManager recyclerViewlayoutManager;
    ShowReviewListAdapter recyclerViewadapter;
    RecyclerView review_recycler_view;
    SwipeRefreshLayout refresh_layout;

    CamomileSpinner progressBar;
    RelativeLayout transparent_layer,progressDialog;

    TextView total_review_tv;

    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.review_list_fragment, container, false);
        FrameLayout frameLayout = view.findViewById(R.id.review_list_main);

        FontHelper.applyFont(getContext(),frameLayout, AllConstants.verdana);

        sPref = getContext().getSharedPreferences(PreferenceClass.user, Context.MODE_PRIVATE);
        total_review_tv = view.findViewById(R.id.total_review_tv);
        initUI(view);
        showRatingList();
      //  total_review_tv.setText(listDataReview.size());
        return view;
    }

    public void initUI(View v){

        review_recycler_view = v.findViewById(R.id.review_list_recycler_view);
        progressBar = v.findViewById(R.id.reviewProgress);
         progressBar.start();
        progressDialog = v.findViewById(R.id.progressDialog);
        transparent_layer = v.findViewById(R.id.transparent_layer);

        review_recycler_view.setHasFixedSize(true);
        recyclerViewlayoutManager = new LinearLayoutManager(getContext());
        review_recycler_view.setLayoutManager(recyclerViewlayoutManager);

        refresh_layout = v.findViewById(R.id.swipe_refresh);
        refresh_layout.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {

                showRatingList();

                refresh_layout.setRefreshing(false);
            }
        });



        back_icon = v.findViewById(R.id.back_icon_review_list);
        back_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if(RProfileFragment.RIDER_REVIEW){
                    RProfileFragment fragmentChild = new RProfileFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.review_list_main, fragmentChild);
                    transaction.addToBackStack(null);
                    transaction.commit();
                    RProfileFragment.RIDER_REVIEW = false;
                }else {
                    RestaurantMenuItemsFragment fragmentChild = new RestaurantMenuItemsFragment();
                    FragmentTransaction transaction = getFragmentManager().beginTransaction();
                    transaction.replace(R.id.review_list_main, fragmentChild);
                    transaction.addToBackStack(null);
                    transaction.commit();
                }
            }
        });


    }


    public void showRatingList(){

        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,false);
        transparent_layer.setVisibility(View.VISIBLE);
        progressDialog.setVisibility(View.VISIBLE);
        String rest_id = sPref.getString(PreferenceClass.RESTAURANT_ID,"");
        String user_id = sPref.getString(PreferenceClass.pre_user_id,"");
        listDataReview = new ArrayList<>();
        RequestQueue queue = Volley.newRequestQueue(getContext());
        JSONObject jsonObject = new JSONObject();
        try {
            if(RProfileFragment.RIDER_REVIEW){
                jsonObject.put("user_id",user_id);
            }
            else {
                jsonObject.put("restaurant_id", rest_id);
            }
        } catch (JSONException e) {
            e.printStackTrace();
        }

        String REVIEW_API;
        if(RProfileFragment.RIDER_REVIEW){
            REVIEW_API = Config.SHOW_RIDER_REVIEW;
        }
        else {
            REVIEW_API = Config.SHOE_TOTAL_RATINGS;
        }

        JsonObjectRequest jsonObjectRequest = new JsonObjectRequest(Request.Method.POST, REVIEW_API, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String strJson =  response.toString();
                JSONObject jsonResponse = null;
                try {
                    jsonResponse = new JSONObject(strJson);

                    Log.d("JSONPost", jsonResponse.toString());

                    int code_id = Integer.parseInt(jsonResponse.optString("code"));

                    if (code_id == 200) {

                        JSONObject json = new JSONObject(jsonResponse.toString());
                        JSONArray jsonarray = json.getJSONArray("msg");

                        for (int i = 0; i<jsonarray.length();i++){

                            JSONObject jsonObject1 = jsonarray.getJSONObject(i);
                            JSONArray commentArray = jsonObject1.getJSONArray("comments");

                            for (int j = 0; j<commentArray.length();j++){

                                JSONObject jsonObject2 = commentArray.getJSONObject(j);
                                JSONObject restaurantRating;
                                if(RProfileFragment.RIDER_REVIEW){
                                 restaurantRating = jsonObject2.getJSONObject("RiderRating");
                                }
                                else {
                                   restaurantRating = jsonObject2.getJSONObject("RestaurantRating");
                                }
                                JSONObject userInfo = jsonObject2.getJSONObject("UserInfo");

                                RatingListModel ratingListModel = new RatingListModel();

                                ratingListModel.setComment(restaurantRating.optString("comment"));
                                ratingListModel.setCreated(restaurantRating.optString("created"));
                                ratingListModel.setRating(restaurantRating.optString("star"));
                                ratingListModel.setF_name(userInfo.optString("first_name"));
                                ratingListModel.setL_name(userInfo.optString("last_name"));

                                listDataReview.add(ratingListModel);
                            }

                        }

                        if(listDataReview!=null) {

                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                            recyclerViewadapter = new ShowReviewListAdapter(listDataReview, getContext());
                            review_recycler_view.setAdapter(recyclerViewadapter);
                            recyclerViewadapter.notifyDataSetChanged();

                            total_review_tv.setText(String.valueOf(listDataReview.size())+ " REVIEWS");
                        }
                        else {
                            TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                            transparent_layer.setVisibility(View.GONE);
                            progressDialog.setVisibility(View.GONE);
                        }

                    }else {
                        TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                        transparent_layer.setVisibility(View.GONE);
                        progressDialog.setVisibility(View.GONE);
                    }



            }catch (JSONException e){

                    e.getCause();
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                TabLayoutUtils.enableTabs(PagerMainActivity.tabLayout,true);
                transparent_layer.setVisibility(View.GONE);
                progressDialog.setVisibility(View.GONE);
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
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

        queue.add(jsonObjectRequest);

    }

}
