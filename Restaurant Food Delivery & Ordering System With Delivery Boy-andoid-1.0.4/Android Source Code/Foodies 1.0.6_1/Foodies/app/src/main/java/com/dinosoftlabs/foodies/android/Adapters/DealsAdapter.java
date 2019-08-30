package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.android.volley.toolbox.ImageLoader;
import com.dinosoftlabs.foodies.android.Constants.Config;
import com.dinosoftlabs.foodies.android.Models.DealsModel;
import com.dinosoftlabs.foodies.android.R;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;

import de.hdodenhof.circleimageview.CircleImageView;

/**
 * Created by Nabeel on 12/27/2017.
 */

public class DealsAdapter extends RecyclerView.Adapter<DealsAdapter.ViewHolder>  {

    ArrayList<DealsModel> getDataAdapter;
    Context context;
    ImageLoader imageLoader1;
    OnItemClickListner onItemClickListner;

    public DealsAdapter(ArrayList<DealsModel> getDataAdapter, Context context){
        super();
        this.getDataAdapter = getDataAdapter;
        this.context = context;
    }

    @Override
    public DealsAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_items_deals, parent, false);

        DealsAdapter.ViewHolder viewHolder = new DealsAdapter.ViewHolder(v);

        return viewHolder;
    }

    @Override
    public void onBindViewHolder(ViewHolder holder, final int position) {
        DealsModel getDataAdapter1 =  getDataAdapter.get(position);

        holder.featured_img.setTag(getDataAdapter1);
        DealsModel checkWetherToShow=(DealsModel)holder.featured_img.getTag();

        imageLoader1 = ServerImageParseAdapter.getInstance(context).getImageLoader();
                Picasso.with(context).load(Config.imgBaseURL+getDataAdapter1.getDeal_image()).
                        fit().centerCrop()
                        .placeholder(R.drawable.unknown_deal)
                        .error(R.drawable.unknown_deal).into(holder.deal_img);

                String expiry_date = getDataAdapter1.getDeal_expiry_date();

        try{String finalExpiryDate = expiry_date.substring(0,10);
            holder.deal_expiry_date.setText(finalExpiryDate);
        }
        catch (StringIndexOutOfBoundsException e){

            e.getCause();
        }
                holder.per_km_deal_tv.setText(getDataAdapter1.getDeal_symbol()+" "+ getDataAdapter1.getDeal_delivery_fee()+"/km");
                holder.deal_name.setText(getDataAdapter1.getDeal_name());
                holder.restaurant_name.setText(getDataAdapter1.getRestaurant_name());
                holder.deal_price.setText(getDataAdapter1.getDeal_symbol()+" "+ getDataAdapter1.getDeal_price());

                holder.row_item_deal_main.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View view) {
                        onItemClickListner.OnItemClicked(view,position);
                    }
                });

                String getPromoted = getDataAdapter1.getPromoted();
                if (checkWetherToShow.getPromoted().equalsIgnoreCase("1")){
                    holder.featured_img.setVisibility(View.VISIBLE);
                }
                else {
                    holder.featured_img.setVisibility(View.GONE);
                }

    }

    @Override
    public int getItemCount() {
        return getDataAdapter.size() ;
    }



    class ViewHolder extends RecyclerView.ViewHolder{

        public TextView restaurant_name,deal_name,deal_price,deal_expiry_date,per_km_deal_tv;
        public CircleImageView deal_img;
        public LinearLayout row_item_deal_main;
        public ImageView featured_img;
        public ViewHolder(View itemView) {

            super(itemView);



            deal_name = (TextView)itemView.findViewById(R.id.deal_name) ;
            restaurant_name = (TextView)itemView.findViewById(R.id.hotal_name_tv) ;
            deal_price = (TextView)itemView.findViewById(R.id.price_deal_tv) ;
            deal_expiry_date = (TextView)itemView.findViewById(R.id.date_deal_tv) ;
            per_km_deal_tv = (TextView)itemView.findViewById(R.id.per_km_deal_tv);
            deal_img = (CircleImageView) itemView.findViewById(R.id.deals_image) ;
            row_item_deal_main = itemView.findViewById(R.id.row_item_deal_main);
            featured_img = itemView.findViewById(R.id.featured_img);



        }
    }

    public interface OnItemClickListner {
        void OnItemClicked(View view, int position);
    }

    public void setOnItemClickListner(OnItemClickListner onItemClickListner) {
        this.onItemClickListner = onItemClickListner;
    }

}