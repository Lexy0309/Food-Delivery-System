package com.dinosoftlabs.foodies.android.Adapters;

import android.content.Context;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ImageView;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.dinosoftlabs.foodies.android.HActivitiesAndFragment.HOrderHistory;
import com.dinosoftlabs.foodies.android.Models.OrderModelClass;
import com.dinosoftlabs.foodies.android.R;
import com.dinosoftlabs.foodies.android.ActivitiesAndFragments.OrdersFragment;


import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.StringTokenizer;

/**
 * Created by Nabeel on 12/26/2017.
 */

public class OrderAdapter extends RecyclerView.Adapter<OrderAdapter.ViewHolder>  {

    ArrayList<OrderModelClass> getDataAdapter;
    Context context;
    OrderAdapter.OnItemClickListner onItemClickListner;

    public OrderAdapter(ArrayList<OrderModelClass> getDataAdapter, Context context){
        super();
        this.getDataAdapter = getDataAdapter;
        this.context = context;
    }

    @Override
    public OrderAdapter.ViewHolder onCreateViewHolder(ViewGroup parent, int viewType) {
        View v = LayoutInflater.from(parent.getContext()).inflate(R.layout.row_items_orders, parent, false);

        OrderAdapter.ViewHolder viewHolder = new OrderAdapter.ViewHolder(v);

        return viewHolder;
    }
    @SuppressWarnings("deprecation")
    @Override
    public void onBindViewHolder(ViewHolder holder, final int position) {
        OrderModelClass getDataAdapter1 =  getDataAdapter.get(position);

        holder.track_tv.setTag(getDataAdapter1);
        holder.deal_img.setTag(getDataAdapter1);
        OrderModelClass checkWetherToShow=(OrderModelClass)holder.track_tv.getTag();
        OrderModelClass checkWetherToShowDeal=(OrderModelClass)holder.deal_img.getTag();

        String date_time = getDataAdapter1.getOrder_created();

     //   String deliver = getDataAdapter1.getDelivery();

        if(OrdersFragment.STATUS_INACTIVE){
            holder.track_tv.setTextColor(context.getResources().getColor(R.color.trackColor));
        }

        else {

            if(HOrderHistory.FLAG_HOTEL_ORDER_HISTORY){
                holder.track_tv.setVisibility(View.GONE);
            }
            else {

                if (checkWetherToShow.getDelivery().equalsIgnoreCase("0")) {
                    holder.track_tv.setTextColor(context.getResources().getColor(R.color.trackColor));
                } else {
                    holder.track_tv.setTextColor(context.getResources().getColor(R.color.colorRed));
                }
            }
        }
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
            holder.order_time.setText(finalTime);
            // <-- I got result here
        } catch (ParseException e) {
            // TODO Auto-generated catch block
            e.printStackTrace();
        }

        holder.menu_item_name.setText(getDataAdapter1.getOrder_name());

        holder.retaurant_name.setText(getDataAdapter1.getRestaurant_name());
       // holder.order_quantity.setText("x"+ getDataAdapter1.getOrder_quantity() +" " + getDataAdapter1.getOrder_extra_item_name());
        holder.order_date.setText(date);
        holder.order_number.setText("Order #"+getDataAdapter1.getOrder_id());
        holder.order_price.setText(getDataAdapter1.getCurrency_symbol()+ getDataAdapter1.getOrder_price());

        if(!checkWetherToShowDeal.getDeal_id().equalsIgnoreCase("0")){
            holder.deal_img.setVisibility(View.VISIBLE);
        }
        else {
            holder.deal_img.setVisibility(View.GONE);
        }

        holder.order_item_main.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {

                try {
                    onItemClickListner.OnItemClicked(v,position);
                }catch (NullPointerException e){
                    e.printStackTrace();
                }

            }
        });

    }

    @Override
    public int getItemCount() {
        return getDataAdapter.size() ;
    }

    class ViewHolder extends RecyclerView.ViewHolder{

        public TextView menu_item_name,retaurant_name, order_date, order_time, order_price,order_number,track_tv;

        RelativeLayout order_item_main;
        ImageView deal_img;


        public ViewHolder(View itemView) {

            super(itemView);
            menu_item_name = (TextView)itemView.findViewById(R.id.deal_name);
            retaurant_name = (TextView)itemView.findViewById(R.id.hotal_name_tv);
          //  order_quantity = (TextView) itemView.findViewById(R.id.order_quantity) ;

            order_date = (TextView)itemView.findViewById(R.id.date_deal_tv);
            order_time = (TextView)itemView.findViewById(R.id.time_deal_tv);
            order_price = (TextView) itemView.findViewById(R.id.price_deal_tv) ;
            order_number = (TextView)itemView.findViewById(R.id.order_number);
            track_tv = itemView.findViewById(R.id.track_tv);
            deal_img = itemView.findViewById(R.id.deal_img);

            order_item_main = (RelativeLayout) itemView.findViewById(R.id.order_item_main_div);

        }
    }

    public interface OnItemClickListner {
        void OnItemClicked(View view, int position);
    }

    public void setOnItemClickListner(OnItemClickListner onItemClickListner) {
        this.onItemClickListner = onItemClickListner;
    }

}
