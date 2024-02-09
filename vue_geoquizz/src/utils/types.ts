import { LatLngExpression } from 'leaflet'

// @TODO: modifier pour correspondre à la DB

export type Serie = {
    id: number;
    city: string;
    cityCenter: LatLngExpression;
    photos: Photo[]
}
export type Photo = {
    id: number;
    imageUrl: string;
    coordinates: LatLngExpression;
}
export type Profile = {
    id: number;
    username: string;
    email: string;
    password: string;
    avatar: string;
    totalScore: number;
    gamesPlayed: number;
}
export type Game = {
    id: number;
    serieId: Serie;
    score: number;
    date: string;
    status: 'archived' | 'in_progress' | 'finished';
    public: boolean;
}
export type CurrentGame = {
    game: Game;
    serie: Serie;
    advancements: Photo[];
}
export type User = {
    id: number;
    username: string;
    email: string;
    avatar: string;
    totalScore: number;
    gamesPlayed: number;
    accessToken: string;
    refreshToken: string;
}
export type PartialAuthUser = {
    id: number;
    accessToken: string;
    refreshToken: string;
}
export type GameProgression = {
    status: 'in_progress' | 'pause' | 'inter_serie' | 'finished';
}