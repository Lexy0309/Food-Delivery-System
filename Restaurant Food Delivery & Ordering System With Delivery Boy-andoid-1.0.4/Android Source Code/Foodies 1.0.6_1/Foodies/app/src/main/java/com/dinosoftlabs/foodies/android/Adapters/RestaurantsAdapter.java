package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.content.SharedPreferences;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.ImageView;
import android.widget.RatingBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.ImageLoader;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;
import com.gmail.samehadar.iosdialog.CamomileSpinner;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.ShowFavoriteRestFragment;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Constants.PreferenceClass;
import com.dinosoftlabs.foodies.android.Models.RestaurantsModel;
import com.dinosoftlabs.foodies.android.R;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

import de.hdodenhof.circleimageview.CircleImageView;

/**
 * Created by Nabeel on 12/20/2017.
 */

public class RestaurantsAdapter extends RecyclerView.Adapter<RestaurantsAdapter.ViewHolder> implements Filterable {

    ArrayList<RestaurantsModel> getDataAdapter;
    private ArrayList<RestaurantsModel> mFilteredList;
    Context context;
    ImageLoader imageLoader1;
    OnItemClickListner onItemClickListner;
    SharedPreferences sharedPreferences;
    CamomileSpinner progressBar;
    ShowFavoriteRestFragment fragment;

    public RestaurantsAdapter(ArrayList<RestaurantsModel> getDataAdapter, Context context,ShowFavoriteRestFragment fragment,CamomileSpinner progressBar){
        super();
        this.getDataAdapter = getDataAdapter;
        mFilteredList = getDataAdapter;
        this.context = context;
        this.progressBar = progressBar;
        this.fragment = fragment;
    }

    @Override
    public RestaurantsAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = null;
       /* if(viewType == 1) {
           v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_item_view_pager, parent, false);
        }
        else {*/
            v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_items_restaurants, parent, false);
      //  }

        ViewHolder viewHolder = new ViewHolder(v);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(final RestaurantsAdapter.ViewHolder holder, final int position) {

        final RestaurantsModel getDataAdapter1 =  mFilteredList.get(position);

        sharedPreferences = context.getSharedPreferences(PreferenceClass.user,Context.MODE_PRIVATE);
        imageLoader1 = ServerImageParseAdapter.getInstance(context).getImageLoader();
        progressBar.start();

//        imageLoader1.get(getDataAdapter1.getRestaurant_image(),
            //ImageLoader.getImageListener(
                  //      holder.restaurant_img,//Server Image
                  //      R.mipmap.ic_launcher,//Before loading server image the default showing image.
                    //    android.R.drawable.ic_dialog_alert  );  //Error image if requested image dose not found on server.

        Picasso.with(context).load(Config.imgBaseURL+getDataAdapter1.getRestaurant_image()).
        fit().centerCrop()
                .placeholder(R.drawable.unknown_img)
                .error(R.drawable.unknown_img).into(holder.restaurant_img);


           holder.title_restaurants.setText(getDataAdapter1.getRestaurant_name().trim());


        String symbol = getDataAdapter1.getRestaurant_currency();
        holder.salogon_restaurants.setText(getDataAdapter1.getRestaurant_salgon().trim());
        holder.item_price_tv.setText(getDataAdapter1.getPreparation_time()+ " min");
      //  holder.item_price_per_mile.setText(symbol+" "+getDataAdapter1.getDelivery_fee_per_km()+" / over"+" "+symbol+" "+getDataAdapter1.getMin_order_price());

        holder.ratingBar.setRating(Float.parseFloat(getDataAdapter1.getRestaurant_avgRating()));
        holder.item_time_tv.setText(symbol+" "+getDataAdapter1.getDelivery_fee_per_km()+" / over"+" "+symbol+" "+getDataAdapter1.getMin_order_price());
    //  holder.item_time_tv.setText(getDataAdapter1.getPreparation_time()+ " min");
    //  holder.distanse_restaurants.setText(getDataAdapter1.getRestaurant_distance());

        String getFavoriteStatus = getDataAdapter1.getRestaurant_isFav();

        if (getFavoriteStatus.equalsIgnoreCase("1")){
            holder.favorite_icon.setImageResource(R.drawable.ic_heart_filled);
        }
        else {
            holder.favorite_icon.setImageResource(R.drawable.ic_heart_not_filled);
        }

        String getPromotedString = getDataAdapter1.getPromoted();

        if (getPromotedString.equalsIgnoreCase("1"))
        {
            holder.featured.setVisibility(View.VISIBLE);
        }
        else {
            holder.featured.setVisibility(View.GONE);
        }

        holder.distanse_restaurants.setText(getDataAdapter1.getRestaurant_distance());

        holder.restaurant_row_main.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

                if (onItemClickListner !=null){
                    int position = holder.getAdapterPosition();
                    String name = mFilteredList.get(position).getRestaurant_id();
                    for (int i=0 ; i <getDataAdapter.size() ; i++ ){
                        if(name.equals(getDataAdapter.get(i).getRestaurant_id())){
                            position = i;
                            break;
                        }
                    }
                    if (position != RecyclerView.NO_POSITION) {
                        onItemClickListner.OnItemClicked(view,position);
                    }
                }
            }
        });


        holder.favorite_icon.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View view) {

              //  Toast.makeText(context,String.valueOf(position),Toast.LENGTH_SHORT).show();
                addFavoriteRestaurant(getDataAdapter1.getRestaurant_id());


            }
        });

    }

    @Override
    public int getItemViewType(int position) {
        if (position == 0)
            return 1;
        else
            return 2;
    }

    @Override
    public int getItemCount() {
        return mFilteredList.size() ;
    }
    @SuppressWarnings("unchecked")
    @Override
    public Filter getFilter() {

        return new Filter() {
            @Override
            protected FilterResults performFiltering(CharSequence charSequence) {
                String charString = charSequence.toString();
                if (charString.isEmpty()) {
                    mFilteredList = getDataAdapter;
                } else {
                    ArrayList<RestaurantsModel> filteredList = new ArrayList<>();
                    for (RestaurantsModel row : getDataAdapter) {

                        // name match condition. this might differ depending on your requirement
                        // here we are looking for name or phone number match
                        if (row.getRestaurant_name().toLowerCase().contains(charString.toLowerCase()) || row.getRestaurant_name().contains(charSequence)) {
                            filteredList.add(row);
                        }
                    }

                    mFilteredList = filteredList;
                }

                FilterResults filterResults = new FilterResults();
                filterResults.values = mFilteredList;
                return filterResults;
            }

            @Override
            protected void publishResults(CharSequence charSequence, FilterResults filterResults) {
                mFilteredList = (ArrayList<RestaurantsModel>) filterResults.values;
                notifyDataSetChanged();
            }
        };


    }



    public class ViewHolder extends RecyclerView.ViewHolder{

        public TextView title_restaurants,distanse_restaurants,salogon_restaurants,item_price_tv,item_time_tv;
        public CircleImageView restaurant_img;
        public RelativeLayout restaurant_row_main;
        public RatingBar ratingBar;
        public ImageView favorite_icon,featured;

        public ViewHolder(View itemView) {

            super(itemView);
            title_restaurants = (TextView)itemView.findViewById(R.id.title_restaurants);
            salogon_restaurants = (TextView)itemView.findViewById(R.id.salogon_restaurants);
            distanse_restaurants = (TextView) itemView.findViewById(R.id.distanse_restaurants) ;
           // item_price_per_mile = itemView.findViewById(R.id.item_price_per_mile);
            item_price_tv = itemView.findViewById(R.id.item_delivery_time_tv);

            restaurant_img = (CircleImageView) itemView.findViewById(R.id.profile_image_restaurant) ;
            restaurant_row_main = itemView.findViewById(R.id.restaurant_row_main);
            ratingBar = itemView.findViewById(R.id.ruleRatingBar);
            favorite_icon = itemView.findViewById(R.id.favorite_icon);
            featured = itemView.findViewById(R.id.featured);
            item_time_tv = itemView.findViewById(R.id.item_time_tv);

        }
    }


    public interface OnItemClickListner {
        void OnItemClicked(View view, int position);
    }

    public void setOnItemClickListner(OnItemClickListner onCardClickListner) {
        this.onItemClickListner = onCardClickListner;
    }


    public void addFavoriteRestaurant(String res_id){
        progressBar.setVisibility(View.VISIBLE);
        final String user_id = sharedPreferences.getString(PreferenceClass.pre_user_id,"");
        RequestQueue queue = Volley.newRequestQueue(context);

        JSONObject jsonObject = new JSONObject();

        try {
            jsonObject.put("user_id",user_id);
            jsonObject.put("restaurant_id",res_id);
            jsonObject.put("favourite","1");

        } catch (JSONException e) {
            e.printStackTrace();
        }

        JsonObjectRequest favJsonRequest = new JsonObjectRequest(Request.Method.POST, Config.ADD_FAV_RESTAURANT, jsonObject, new Response.Listener<JSONObject>() {
            @Override
            public void onResponse(JSONObject response) {

                String resposeStr = response.toString();
                JSONObject converResponseToJson = null;

                try {
                    converResponseToJson = new JSONObject(resposeStr);

                    int code_id  = Integer.parseInt(converResponseToJson.optString("code"));
                    if(code_id == 200) {

                       /* ArrayList<RestaurantsModel> tempModel=new ArrayList<RestaurantsModel>();
                        tempModel.clear();
                        tempModel.addAll(tempModel);
                        progressBar.setVisibility(View.GONE);*/

                        fragment.getRestaurantList(user_id);
                     //   progressBar.setVisibility(View.GONE);
                        ShowFavoriteRestFragment.FROM_FAVORITE = true;
                        notifyDataSetChanged();
                  /*      new Handler(Looper.getMainLooper()).post(new Runnable() {
                            public void run() {
                                progressBar.setVisibility(View.GONE);
                                notifyDataSetChanged();
                            }
                        });*/
                       // Toast.makeText(getApplicationContext(),converResponseToJson.optString("msg"),Toast.LENGTH_LONG).show();


                    }
                    else {
                        progressBar.setVisibility(View.GONE);
                    }


                } catch (JSONException e) {
                    e.printStackTrace();
                }


            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d("JSONPost", "Error: " + error.getMessage());
                Toast.makeText(context, error.getMessage(), Toast.LENGTH_SHORT).show();
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

        queue.add(favJsonRequest);
    }


}
