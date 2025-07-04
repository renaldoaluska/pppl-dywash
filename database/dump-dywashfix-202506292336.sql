PGDMP  -    $                }         	   dywashfix    17.5    17.5 X    k           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            l           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            m           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            n           1262    19305 	   dywashfix    DATABASE     �   CREATE DATABASE dywashfix WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Indonesian_Indonesia.1252';
    DROP DATABASE dywashfix;
                     postgres    false                        2615    2200    public    SCHEMA        CREATE SCHEMA public;
    DROP SCHEMA public;
                     pg_database_owner    false            o           0    0    SCHEMA public    COMMENT     6   COMMENT ON SCHEMA public IS 'standard public schema';
                        pg_database_owner    false    4            e           1247    19322    order_status_enum    TYPE     �   CREATE TYPE public.order_status_enum AS ENUM (
    'diterima',
    'ditolak',
    'diambil',
    'dicuci',
    'dikirim',
    'selesai',
    'diulas'
);
 $   DROP TYPE public.order_status_enum;
       public               postgres    false    4            b           1247    19314    outlet_status    TYPE     \   CREATE TYPE public.outlet_status AS ENUM (
    'pending',
    'verified',
    'rejected'
);
     DROP TYPE public.outlet_status;
       public               postgres    false    4            h           1247    19338    payment_method_enum    TYPE     ]   CREATE TYPE public.payment_method_enum AS ENUM (
    'transfer',
    'cod',
    'ewallet'
);
 &   DROP TYPE public.payment_method_enum;
       public               postgres    false    4            k           1247    19346    payment_status_enum    TYPE     g   CREATE TYPE public.payment_status_enum AS ENUM (
    'pending',
    'lunas',
    'gagal',
    'cod'
);
 &   DROP TYPE public.payment_status_enum;
       public               postgres    false    4            _           1247    19307 	   user_role    TYPE     P   CREATE TYPE public.user_role AS ENUM (
    'admin',
    'outlet',
    'cust'
);
    DROP TYPE public.user_role;
       public               postgres    false    4            �            1259    19369 	   addresses    TABLE     �  CREATE TABLE public.addresses (
    address_id integer NOT NULL,
    user_id integer NOT NULL,
    label character varying(100) NOT NULL,
    recipient_name character varying(255) NOT NULL,
    phone_number character varying(20) NOT NULL,
    address_detail text NOT NULL,
    latitude double precision NOT NULL,
    longitude double precision NOT NULL,
    is_primary boolean DEFAULT false
);
    DROP TABLE public.addresses;
       public         heap r       postgres    false    4            �            1259    19368    addresses_address_id_seq    SEQUENCE     �   CREATE SEQUENCE public.addresses_address_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.addresses_address_id_seq;
       public               postgres    false    4    220            p           0    0    addresses_address_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public.addresses_address_id_seq OWNED BY public.addresses.address_id;
          public               postgres    false    219            �            1259    19456    order_items    TABLE     �   CREATE TABLE public.order_items (
    item_id integer NOT NULL,
    order_id integer NOT NULL,
    service_id integer NOT NULL,
    quantity numeric(10,2) NOT NULL,
    subtotal numeric(10,2) NOT NULL
);
    DROP TABLE public.order_items;
       public         heap r       postgres    false    4            �            1259    19455    order_items_item_id_seq    SEQUENCE     �   CREATE SEQUENCE public.order_items_item_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.order_items_item_id_seq;
       public               postgres    false    4    230            q           0    0    order_items_item_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.order_items_item_id_seq OWNED BY public.order_items.item_id;
          public               postgres    false    229            �            1259    19413    orders    TABLE     �  CREATE TABLE public.orders (
    order_id integer NOT NULL,
    customer_id integer NOT NULL,
    outlet_id integer NOT NULL,
    orders_address_id integer,
    order_date timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    total_amount numeric(10,2),
    status public.order_status_enum DEFAULT 'diterima'::public.order_status_enum,
    customer_notes text,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
    DROP TABLE public.orders;
       public         heap r       postgres    false    869    869    4            �            1259    19436    orders_address    TABLE     �  CREATE TABLE public.orders_address (
    order_address_id integer NOT NULL,
    order_id integer NOT NULL,
    label character varying(100) NOT NULL,
    recipient_name character varying(255) NOT NULL,
    phone_number character varying(20) NOT NULL,
    address_detail text NOT NULL,
    latitude double precision NOT NULL,
    longitude double precision NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
 "   DROP TABLE public.orders_address;
       public         heap r       postgres    false    4            �            1259    19435 #   orders_address_order_address_id_seq    SEQUENCE     �   CREATE SEQUENCE public.orders_address_order_address_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 :   DROP SEQUENCE public.orders_address_order_address_id_seq;
       public               postgres    false    4    228            r           0    0 #   orders_address_order_address_id_seq    SEQUENCE OWNED BY     k   ALTER SEQUENCE public.orders_address_order_address_id_seq OWNED BY public.orders_address.order_address_id;
          public               postgres    false    227            �            1259    19412    orders_order_id_seq    SEQUENCE     �   CREATE SEQUENCE public.orders_order_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 *   DROP SEQUENCE public.orders_order_id_seq;
       public               postgres    false    4    226            s           0    0    orders_order_id_seq    SEQUENCE OWNED BY     K   ALTER SEQUENCE public.orders_order_id_seq OWNED BY public.orders.order_id;
          public               postgres    false    225            �            1259    19384    outlets    TABLE       CREATE TABLE public.outlets (
    outlet_id integer NOT NULL,
    owner_id integer NOT NULL,
    name character varying(255) NOT NULL,
    address text NOT NULL,
    latitude double precision,
    longitude double precision,
    contact_phone character varying(20),
    operating_hours character varying(255),
    status public.outlet_status DEFAULT 'pending'::public.outlet_status,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
    DROP TABLE public.outlets;
       public         heap r       postgres    false    866    866    4            �            1259    19383    outlets_outlet_id_seq    SEQUENCE     �   CREATE SEQUENCE public.outlets_outlet_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.outlets_outlet_id_seq;
       public               postgres    false    222    4            t           0    0    outlets_outlet_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE public.outlets_outlet_id_seq OWNED BY public.outlets.outlet_id;
          public               postgres    false    221            �            1259    19473    payments    TABLE     ?  CREATE TABLE public.payments (
    payment_id integer NOT NULL,
    order_id integer NOT NULL,
    amount numeric(10,2) NOT NULL,
    payment_method public.payment_method_enum NOT NULL,
    status public.payment_status_enum DEFAULT 'pending'::public.payment_status_enum,
    payment_date timestamp without time zone
);
    DROP TABLE public.payments;
       public         heap r       postgres    false    875    872    4    875            �            1259    19472    payments_payment_id_seq    SEQUENCE     �   CREATE SEQUENCE public.payments_payment_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.payments_payment_id_seq;
       public               postgres    false    4    232            u           0    0    payments_payment_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.payments_payment_id_seq OWNED BY public.payments.payment_id;
          public               postgres    false    231            �            1259    19486    reviews    TABLE     f  CREATE TABLE public.reviews (
    review_id integer NOT NULL,
    order_id integer NOT NULL,
    customer_id integer NOT NULL,
    outlet_id integer NOT NULL,
    rating integer NOT NULL,
    comment text,
    review_date timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT reviews_rating_check CHECK (((rating >= 1) AND (rating <= 5)))
);
    DROP TABLE public.reviews;
       public         heap r       postgres    false    4            �            1259    19485    reviews_review_id_seq    SEQUENCE     �   CREATE SEQUENCE public.reviews_review_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 ,   DROP SEQUENCE public.reviews_review_id_seq;
       public               postgres    false    234    4            v           0    0    reviews_review_id_seq    SEQUENCE OWNED BY     O   ALTER SEQUENCE public.reviews_review_id_seq OWNED BY public.reviews.review_id;
          public               postgres    false    233            �            1259    19401    services    TABLE     �   CREATE TABLE public.services (
    service_id integer NOT NULL,
    outlet_id integer NOT NULL,
    name character varying(255) NOT NULL,
    price numeric(10,2) NOT NULL,
    unit character varying(50) NOT NULL
);
    DROP TABLE public.services;
       public         heap r       postgres    false    4            �            1259    19400    services_service_id_seq    SEQUENCE     �   CREATE SEQUENCE public.services_service_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.services_service_id_seq;
       public               postgres    false    4    224            w           0    0    services_service_id_seq    SEQUENCE OWNED BY     S   ALTER SEQUENCE public.services_service_id_seq OWNED BY public.services.service_id;
          public               postgres    false    223            �            1259    19356    users    TABLE     o  CREATE TABLE public.users (
    user_id integer NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    role public.user_role NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);
    DROP TABLE public.users;
       public         heap r       postgres    false    863    4            �            1259    19355    users_user_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.users_user_id_seq;
       public               postgres    false    218    4            x           0    0    users_user_id_seq    SEQUENCE OWNED BY     G   ALTER SEQUENCE public.users_user_id_seq OWNED BY public.users.user_id;
          public               postgres    false    217            �           2604    19372    addresses address_id    DEFAULT     |   ALTER TABLE ONLY public.addresses ALTER COLUMN address_id SET DEFAULT nextval('public.addresses_address_id_seq'::regclass);
 C   ALTER TABLE public.addresses ALTER COLUMN address_id DROP DEFAULT;
       public               postgres    false    219    220    220            �           2604    19459    order_items item_id    DEFAULT     z   ALTER TABLE ONLY public.order_items ALTER COLUMN item_id SET DEFAULT nextval('public.order_items_item_id_seq'::regclass);
 B   ALTER TABLE public.order_items ALTER COLUMN item_id DROP DEFAULT;
       public               postgres    false    229    230    230            �           2604    19416    orders order_id    DEFAULT     r   ALTER TABLE ONLY public.orders ALTER COLUMN order_id SET DEFAULT nextval('public.orders_order_id_seq'::regclass);
 >   ALTER TABLE public.orders ALTER COLUMN order_id DROP DEFAULT;
       public               postgres    false    225    226    226            �           2604    19439    orders_address order_address_id    DEFAULT     �   ALTER TABLE ONLY public.orders_address ALTER COLUMN order_address_id SET DEFAULT nextval('public.orders_address_order_address_id_seq'::regclass);
 N   ALTER TABLE public.orders_address ALTER COLUMN order_address_id DROP DEFAULT;
       public               postgres    false    228    227    228            �           2604    19387    outlets outlet_id    DEFAULT     v   ALTER TABLE ONLY public.outlets ALTER COLUMN outlet_id SET DEFAULT nextval('public.outlets_outlet_id_seq'::regclass);
 @   ALTER TABLE public.outlets ALTER COLUMN outlet_id DROP DEFAULT;
       public               postgres    false    222    221    222            �           2604    19476    payments payment_id    DEFAULT     z   ALTER TABLE ONLY public.payments ALTER COLUMN payment_id SET DEFAULT nextval('public.payments_payment_id_seq'::regclass);
 B   ALTER TABLE public.payments ALTER COLUMN payment_id DROP DEFAULT;
       public               postgres    false    231    232    232            �           2604    19489    reviews review_id    DEFAULT     v   ALTER TABLE ONLY public.reviews ALTER COLUMN review_id SET DEFAULT nextval('public.reviews_review_id_seq'::regclass);
 @   ALTER TABLE public.reviews ALTER COLUMN review_id DROP DEFAULT;
       public               postgres    false    233    234    234            �           2604    19404    services service_id    DEFAULT     z   ALTER TABLE ONLY public.services ALTER COLUMN service_id SET DEFAULT nextval('public.services_service_id_seq'::regclass);
 B   ALTER TABLE public.services ALTER COLUMN service_id DROP DEFAULT;
       public               postgres    false    224    223    224            �           2604    19359    users user_id    DEFAULT     n   ALTER TABLE ONLY public.users ALTER COLUMN user_id SET DEFAULT nextval('public.users_user_id_seq'::regclass);
 <   ALTER TABLE public.users ALTER COLUMN user_id DROP DEFAULT;
       public               postgres    false    217    218    218            Z          0    19369 	   addresses 
   TABLE DATA           �   COPY public.addresses (address_id, user_id, label, recipient_name, phone_number, address_detail, latitude, longitude, is_primary) FROM stdin;
    public               postgres    false    220   �p       d          0    19456    order_items 
   TABLE DATA           X   COPY public.order_items (item_id, order_id, service_id, quantity, subtotal) FROM stdin;
    public               postgres    false    230   �q       `          0    19413    orders 
   TABLE DATA           �   COPY public.orders (order_id, customer_id, outlet_id, orders_address_id, order_date, total_amount, status, customer_notes, created_at, updated_at) FROM stdin;
    public               postgres    false    226   ]r       b          0    19436    orders_address 
   TABLE DATA           �   COPY public.orders_address (order_address_id, order_id, label, recipient_name, phone_number, address_detail, latitude, longitude, created_at) FROM stdin;
    public               postgres    false    228   �s       \          0    19384    outlets 
   TABLE DATA           �   COPY public.outlets (outlet_id, owner_id, name, address, latitude, longitude, contact_phone, operating_hours, status, created_at, updated_at) FROM stdin;
    public               postgres    false    222   ku       f          0    19473    payments 
   TABLE DATA           f   COPY public.payments (payment_id, order_id, amount, payment_method, status, payment_date) FROM stdin;
    public               postgres    false    232   yv       h          0    19486    reviews 
   TABLE DATA           l   COPY public.reviews (review_id, order_id, customer_id, outlet_id, rating, comment, review_date) FROM stdin;
    public               postgres    false    234   #w       ^          0    19401    services 
   TABLE DATA           L   COPY public.services (service_id, outlet_id, name, price, unit) FROM stdin;
    public               postgres    false    224   �w       X          0    19356    users 
   TABLE DATA           ]   COPY public.users (user_id, name, email, password, role, created_at, updated_at) FROM stdin;
    public               postgres    false    218   ?x       y           0    0    addresses_address_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.addresses_address_id_seq', 4, true);
          public               postgres    false    219            z           0    0    order_items_item_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.order_items_item_id_seq', 10, true);
          public               postgres    false    229            {           0    0 #   orders_address_order_address_id_seq    SEQUENCE SET     R   SELECT pg_catalog.setval('public.orders_address_order_address_id_seq', 10, true);
          public               postgres    false    227            |           0    0    orders_order_id_seq    SEQUENCE SET     B   SELECT pg_catalog.setval('public.orders_order_id_seq', 10, true);
          public               postgres    false    225            }           0    0    outlets_outlet_id_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.outlets_outlet_id_seq', 3, true);
          public               postgres    false    221            ~           0    0    payments_payment_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.payments_payment_id_seq', 10, true);
          public               postgres    false    231                       0    0    reviews_review_id_seq    SEQUENCE SET     C   SELECT pg_catalog.setval('public.reviews_review_id_seq', 1, true);
          public               postgres    false    233            �           0    0    services_service_id_seq    SEQUENCE SET     E   SELECT pg_catalog.setval('public.services_service_id_seq', 6, true);
          public               postgres    false    223            �           0    0    users_user_id_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.users_user_id_seq', 6, true);
          public               postgres    false    217            �           2606    19377    addresses addresses_pkey 
   CONSTRAINT     ^   ALTER TABLE ONLY public.addresses
    ADD CONSTRAINT addresses_pkey PRIMARY KEY (address_id);
 B   ALTER TABLE ONLY public.addresses DROP CONSTRAINT addresses_pkey;
       public                 postgres    false    220            �           2606    19461    order_items order_items_pkey 
   CONSTRAINT     _   ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT order_items_pkey PRIMARY KEY (item_id);
 F   ALTER TABLE ONLY public.order_items DROP CONSTRAINT order_items_pkey;
       public                 postgres    false    230            �           2606    19444 "   orders_address orders_address_pkey 
   CONSTRAINT     n   ALTER TABLE ONLY public.orders_address
    ADD CONSTRAINT orders_address_pkey PRIMARY KEY (order_address_id);
 L   ALTER TABLE ONLY public.orders_address DROP CONSTRAINT orders_address_pkey;
       public                 postgres    false    228            �           2606    19424    orders orders_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (order_id);
 <   ALTER TABLE ONLY public.orders DROP CONSTRAINT orders_pkey;
       public                 postgres    false    226            �           2606    19394    outlets outlets_pkey 
   CONSTRAINT     Y   ALTER TABLE ONLY public.outlets
    ADD CONSTRAINT outlets_pkey PRIMARY KEY (outlet_id);
 >   ALTER TABLE ONLY public.outlets DROP CONSTRAINT outlets_pkey;
       public                 postgres    false    222            �           2606    19479    payments payments_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.payments
    ADD CONSTRAINT payments_pkey PRIMARY KEY (payment_id);
 @   ALTER TABLE ONLY public.payments DROP CONSTRAINT payments_pkey;
       public                 postgres    false    232            �           2606    19495    reviews reviews_pkey 
   CONSTRAINT     Y   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT reviews_pkey PRIMARY KEY (review_id);
 >   ALTER TABLE ONLY public.reviews DROP CONSTRAINT reviews_pkey;
       public                 postgres    false    234            �           2606    19406    services services_pkey 
   CONSTRAINT     \   ALTER TABLE ONLY public.services
    ADD CONSTRAINT services_pkey PRIMARY KEY (service_id);
 @   ALTER TABLE ONLY public.services DROP CONSTRAINT services_pkey;
       public                 postgres    false    224            �           2606    19367    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    218            �           2606    19365    users users_pkey 
   CONSTRAINT     S   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    218            �           2606    19425    orders fk_customer    FK CONSTRAINT     �   ALTER TABLE ONLY public.orders
    ADD CONSTRAINT fk_customer FOREIGN KEY (customer_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
 <   ALTER TABLE ONLY public.orders DROP CONSTRAINT fk_customer;
       public               postgres    false    218    4776    226            �           2606    19501    reviews fk_customer    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT fk_customer FOREIGN KEY (customer_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
 =   ALTER TABLE ONLY public.reviews DROP CONSTRAINT fk_customer;
       public               postgres    false    234    218    4776            �           2606    19462    order_items fk_order    FK CONSTRAINT     �   ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT fk_order FOREIGN KEY (order_id) REFERENCES public.orders(order_id) ON DELETE CASCADE;
 >   ALTER TABLE ONLY public.order_items DROP CONSTRAINT fk_order;
       public               postgres    false    230    226    4784            �           2606    19480    payments fk_order    FK CONSTRAINT     �   ALTER TABLE ONLY public.payments
    ADD CONSTRAINT fk_order FOREIGN KEY (order_id) REFERENCES public.orders(order_id) ON DELETE CASCADE;
 ;   ALTER TABLE ONLY public.payments DROP CONSTRAINT fk_order;
       public               postgres    false    232    226    4784            �           2606    19496    reviews fk_order    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT fk_order FOREIGN KEY (order_id) REFERENCES public.orders(order_id) ON DELETE CASCADE;
 :   ALTER TABLE ONLY public.reviews DROP CONSTRAINT fk_order;
       public               postgres    false    4784    226    234            �           2606    19450    orders fk_orders_address    FK CONSTRAINT     �   ALTER TABLE ONLY public.orders
    ADD CONSTRAINT fk_orders_address FOREIGN KEY (orders_address_id) REFERENCES public.orders_address(order_address_id) ON DELETE RESTRICT;
 B   ALTER TABLE ONLY public.orders DROP CONSTRAINT fk_orders_address;
       public               postgres    false    228    4786    226            �           2606    19407    services fk_outlet    FK CONSTRAINT     �   ALTER TABLE ONLY public.services
    ADD CONSTRAINT fk_outlet FOREIGN KEY (outlet_id) REFERENCES public.outlets(outlet_id) ON DELETE CASCADE;
 <   ALTER TABLE ONLY public.services DROP CONSTRAINT fk_outlet;
       public               postgres    false    222    224    4780            �           2606    19430    orders fk_outlet    FK CONSTRAINT     �   ALTER TABLE ONLY public.orders
    ADD CONSTRAINT fk_outlet FOREIGN KEY (outlet_id) REFERENCES public.outlets(outlet_id) ON DELETE CASCADE;
 :   ALTER TABLE ONLY public.orders DROP CONSTRAINT fk_outlet;
       public               postgres    false    226    4780    222            �           2606    19506    reviews fk_outlet    FK CONSTRAINT     �   ALTER TABLE ONLY public.reviews
    ADD CONSTRAINT fk_outlet FOREIGN KEY (outlet_id) REFERENCES public.outlets(outlet_id) ON DELETE CASCADE;
 ;   ALTER TABLE ONLY public.reviews DROP CONSTRAINT fk_outlet;
       public               postgres    false    4780    222    234            �           2606    19395    outlets fk_owner    FK CONSTRAINT     �   ALTER TABLE ONLY public.outlets
    ADD CONSTRAINT fk_owner FOREIGN KEY (owner_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
 :   ALTER TABLE ONLY public.outlets DROP CONSTRAINT fk_owner;
       public               postgres    false    222    4776    218            �           2606    19467    order_items fk_service    FK CONSTRAINT     �   ALTER TABLE ONLY public.order_items
    ADD CONSTRAINT fk_service FOREIGN KEY (service_id) REFERENCES public.services(service_id);
 @   ALTER TABLE ONLY public.order_items DROP CONSTRAINT fk_service;
       public               postgres    false    4782    230    224            �           2606    19378    addresses fk_user    FK CONSTRAINT     �   ALTER TABLE ONLY public.addresses
    ADD CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON DELETE CASCADE;
 ;   ALTER TABLE ONLY public.addresses DROP CONSTRAINT fk_user;
       public               postgres    false    220    218    4776            �           2606    19445 +   orders_address orders_address_order_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.orders_address
    ADD CONSTRAINT orders_address_order_id_fkey FOREIGN KEY (order_id) REFERENCES public.orders(order_id) ON DELETE CASCADE;
 U   ALTER TABLE ONLY public.orders_address DROP CONSTRAINT orders_address_order_id_fkey;
       public               postgres    false    228    4784    226            Z     x�U��N�0Eד��%H`%~䱌@BiD-K61Ŕ8hj��{�	���:g�-@�6��
��L�F��.�Ҧ��&�ջ�k�q���H��ûI�6pY	Y7
�B�JJ	!�	�&&^�g����H���7v�~�;��x��������>��)���L����8��z�r�	7���,�JimL��	���kˁ���a���-nB2,%Mzg��M*�P.��=��S����L�Ϛ��J�U�>]�M5�Rʐ�}j�.~2M�>�,�~ �9e      d   [   x�e��	�0г<L�/�]����{i��<$�@�1C���!��i�x��2�6�~�:�e�_��Wlae�,�e;��1.��p�9m��iD�xW      `   �  x��T�n�0<�_�(��yp���r�e!5	!��"���!�"�(�d5㙝�mA4�\�O�`R-�Y*�H�S �9O8'�j�d}��͎���A�J��C;t���n��'�`�л��:
Qz���br.���޼��G����Z�͙��ҽ�!��hBɍ$�M���;��~�f��Q���#x���/6���c���"�C����(����\H<��b�>
�Mc��e���k�n�����h�g%Siq~;l��>~Ϙ��8D�q���:�6�;�Ӷ������/�Ի
z���x$K��М�� ��*�B��D��J�Ǿv�*n�ұ�z�zwl��l�ޑ>&%���)���#$��(>s=|���W
8D�J�/v�4�      b   k  x����j1����S�e���;���8�d��IG�Sw4EѤ��#{
!����B��|6cO�a��י
�������?�B�psO.�K��f��>(B6NJ	�K3�v&J5Wf�t#u}����T�<�>�c��������؎i�w]����t��	.cj�ۮΐpE�
~�Ny}$n}�L�:4h�}���;f�� jT��Y�g�Z�$s�N�<0�(������	��S��zR)��q��p=�o��U̅~����w̸��R�S=�������r�4&)��2��q�E�n3u���Y�U%콛zջ��{nkv���I��_�	GzJ���G��}��&0�l�`꿂=4���t&�      \   �   x��пN�0���~�Ʋ�vlgT�,XX��,RS�Է�R;0 ����I��>	wCL�a�4�.a=p�@����gR��fʴ�#Am9za@J���$*m뼀MH�M�6�����(ʆ���.�P��ES�g�ZeZ�9*��%�\��_�(���Es�u�/Z/g-*��1Mю��r,V�X�?XUi�&�C
�Lz��>�יz)���T�R�lc�B���z��X���*Of8�������̫���s}�      f   �   x���I
�0���)z�Y��$��	�1�k
�-���E�"!��_��k"*��>�0!�ُ`bWPUpsb��[��XQ��o5>��0l3�W���[�������~�r�E���t�ѧ5$�#�j�`�']�fi��I�2��m]��z��s�      h   z   x��A
�0E��)�4��FlO�Rp�f$C��SIS��;.>������f�Ds�-��Kp��$������5/$��g��oj�K�\u�-��e]X�^��A����G�0�8����G��Yk��%�      ^   �   x�U�;�0��S����й�YE+kI�"
n��0�z��3``,��G	^]e��u�5�m�(3)Gw�ac};^�Q2��)��_�S�������=%
����S�Sx7Δ��=�u��Q�3�      X   �   x��ϱ�0����}�@"(#���˕�	Ӗ��"��Ɓ���N��I�r�����zx�1Ӡe��:y⇩���L$����(?&��q��*��3��nE�#u�a�]c7�\Ⱦk�Ᏽ
徝5Z���{���F'�Y����^�{%�����m5�"���W���yO���     